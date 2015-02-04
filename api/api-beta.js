// server.js

// BASE SETUP
// =============================================================================

// call the packages we need
var express = require('/usr/local/lib/node_modules/express');
var app = express();

var http = require('http').Server(app);
var socket = require('socket.io');
var redis = require('socket.io-redis');
global.io = socket.listen(http);
global.io.adapter(redis({ host: 'localhost', port: 6379 }));
var bodyParser = require('body-parser');
var crypto = require('crypto');
//var querystring = require('querystring');
var fs = require('fs');
var cluster = require('cluster');
var path = require('path');
var port = process.env.PORT || 8080; // set our port

// Code to run if we're in the master process
if (cluster.isMaster) {

	// Count the machine's CPUs
	var cpuCount = require('os').cpus().length;

	// Create a worker for each CPU
	for (var i = 0; i < cpuCount; i += 1) {
		cluster.fork();
	}

	// Listen for dying workers
	cluster.on('exit', function(worker) {

		// Replace the dead worker, we're not sentimental
		console.log('Worker ' + worker.id + ' died :(');
		cluster.fork();

	});

	// Code to run if we're in a worker process
}

if (cluster.isWorker) {

	global.users = {};
	var numUsers = 0;
	var songsPlaying = [];
	io.sockets.on('connection', function(socket) {
		socket.on('sync', function(model, url) {

		});

		socket.emit('beingStreamed', {
			beingStreamed: songsPlaying
		});
		socket.on('newUser', function(ip) {
			++numUsers;
			global.users[socket.id] = {};
			global.users[socket.id].ip = ip;

			//All three work for global events (everyone) io.emit, io.sockets.emit, and socket.broadcast.emit
			//io.emit('addUser', { ip: ip, online:numUsers, uid: socket.id });
			socket.broadcast.emit('addUser', {
				ip: ip,
				online: numUsers,
				uid: socket.id
			});
			//io.sockets.emit('addUser', { ip: ip, online:numUsers, uid: socket.id });
			socket.on('disconnect', function(data) {
				//console.log('user disconnected: ' + socket.id + ' - ', data);
				if (numUsers > 0) {
					--numUsers;
					delete global.users[socket.id];
				}
				for (var i = 0, len = songsPlaying.length; i < len; ++i) {
					if (songsPlaying[i].uid === socket.id) {
						socket.broadcast.emit('streamEnded', {
							songId: songsPlaying[i].songId
						});
						songsPlaying.splice(i, 1);
						break;
					}
				}
			});
		});

		socket.on('streamStarted', function(data) {
			var checkTitle = (data.songTitle).replace(/\W/g, '');
			if (checkTitle !== null && checkTitle !== '' && data.songId !== null && data.songId !== '') {
				songsPlaying.push({
					songTitle: data.songTitle,
					songId: data.songId,
					uid: socket.id
				});
				socket.broadcast.emit('songStreaming', {
					songTitle: data.songTitle,
					songId: data.songId,
					totalPlaying: songsPlaying.length
				});
				setTimeout(function() {
					for (var i = 0, len = songsPlaying.length; i < len; ++i) {
						if (songsPlaying[i].songId === data.songId) {
							songsPlaying.splice(i, 1);
							socket.broadcast.emit('streamEnded', {
								songId: data.songId
							});
							break;
						}
					}
				}, 600000); // if still playing after 10min end it manually
			}
		});

		socket.on('streamEnded', function(songId) {
			for (var i = 0, len = songsPlaying.length; i < len; ++i) {
				if (songsPlaying[i].songId === songId) {
					songsPlaying.splice(i, 1);
					break;
				}
			}
			socket.broadcast.emit('streamEnded', {
				songId: songId
			});
		});
	});

	// ROUTES FOR OUR API
	// =============================================================================
	var router = express.Router(); // get an instance of the express Router
	router.all('*', function(req, res, next) {
		console.log(req.method);
		if (req.method === 'OPTIONS') {
			res.header("Access-Control-Allow-Origin", "*");
			res.header("Access-Control-Allow-Headers", "X-Requested-With");
			res.header("Access-Control-Allow-Methods", "POST, GET, PUT, DELETE, OPTIONS");
			res.header("Access-Control-Allow-Credentials", false);
			res.header("Access-Control-Max-Age", '86400'); // 24 hours
			res.header("Access-Control-Allow-Headers", "X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept");
		}
		next();
	});
	// test route to make sure everything is working (accessed at GET http://localhost:8080/api)
	router.get('/', function(req, res) {
		res.send('NodeJS API Online!');
	});

	router.get('/status.json', function(req, res) {
		fs.stat(path.resolve(__dirname, 'api.js'), function(err, stats){
			//res.json({'data': data, 'path': path.resolve(__dirname, 'api.js')});
			if(err){
				res.send('Error Occurred');
			}else{
				res.json({
					status: 'success',
					version: 0.12,
					modified: stats.mtime
				});
			}
		});
	});

	router.get('/app.css', function(req, res) {
		//res.setHeader('content-type', 'text/css');
		res.header("Content-type", "text/css");
		var sass = require('node-sass');

		fs.readFile('/var/zpanel/hostdata/zadmin/public_html/hxcmusic_com/production/scss/app.scss', 'utf-8', function(err, data) {
			if (err) {
				res.send('FATAL An error occurred trying to read in the file: ' + err);
			}
			if (data) {
				sass.render({
					file: '/var/zpanel/hostdata/zadmin/public_html/hxcmusic_com/production/scss/app.scss',
					success: function(css) {
						res.send(css);
					},
					error: function() {
						res.send('Something went wrong!');
					}
				});
			} else {
				res.send('Something went wrong!');
			}
		});
	});

	app.use('/api', router);
	app.use(bodyParser());
	http.listen(port, function() {
		console.log('listening on *: ' + port);
	});
}