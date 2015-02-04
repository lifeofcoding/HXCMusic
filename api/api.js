// server.js

// BASE SETUP
// =============================================================================

// call the packages we need
var express = require('/usr/local/lib/node_modules/express');
var app = express(); // define our app using express
var bodyParser = require('body-parser');
var connect = require('connect');
var crypto = require('crypto');
var session = require('express-session');
var path = require('path');
//var redis = require("redis");
//var client = redis.createClient();
//var server = require('http').createServer(app);
var http = require('http').Server(app);
global.io = require('socket.io')(http);

//var mysql = require('mysql');
var querystring = require('querystring');
var fs = require('fs');
/* var connection = mysql.createConnection({
	host: 'localhost',
	user: 'hxcmusic',
	password: '7y5a2ujat',
	database: 'zadmin_hxcmusic'
}); */



/* http.listen(3000, function(){
  console.log('listening on *:3000');
}); */

/* var numUsers = 0;
var ips = {};
io.on('connection', function (socket) {
	console.log('user connected');
	var addedUser = false;
	++numUsers;
// when the client emits 'add user', this listens and executes
  socket.on('add user', function (ip) {
    // we store the username in the socket session for this client
    socket.ip = ip;
    // add the client's username to the global list
    ips[ip] = 'connected';
    ++numUsers;
    addedUser = true;
	  console.log('user added. ip: ' + ip);
    // echo globally (all clients) that a person has connected
    socket.broadcast.emit('user joined', {
      numUsers: numUsers
    });
  });
}); */

function PostCode(codestring) {
	// Build the post string from an object
	var post_data = querystring.stringify({
		'compilation_level': 'ADVANCED_OPTIMIZATIONS',
		'output_format': 'json',
		'output_info': 'compiled_code',
		'warning_level': 'QUIET',
		'js_code': codestring
	});

	// An object of options to indicate where to post to
	var post_options = {
		host: 'closure-compiler.appspot.com',
		port: '80',
		path: '/compile',
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
			'Content-Length': post_data.length
		}
	};

	// Set up the request
	var post_req = http.request(post_options, function(res) {
		res.setEncoding('utf8');
		res.on('data', function(chunk) {
			console.log('Response: ' + chunk);
		});
	});

	// post the data
	post_req.write(post_data);
	post_req.end();

}

/*
var pool = mysql.createPool({
	connectionLimit: 10,
	host: 'localhost',
	user: 'hxcmusic',
	password: '7y5a2ujat',
	database: 'zadmin_hxcmusic'
});
*/

//connection.connect();

app.use(bodyParser());
//app.use(express.cookieParser());
app.use(session({
	secret: 'keyboard cat',
	cookie: {
		secure: false,
		httpOnly: false
	}
}));



var port = process.env.PORT || 8080; // set our port
//var sessionV;
// ROUTES FOR OUR API
// =============================================================================
var router = express.Router(); // get an instance of the express Router
router.all('*', function(req, res, next) {
	console.log(req.method);
	if(req.method === 'OPTIONS'){
		res.header("Access-Control-Allow-Origin", "*");
		res.header("Access-Control-Allow-Headers", "X-Requested-With");
		res.header("Access-Control-Allow-Methods", "POST, GET, PUT, DELETE, OPTIONS");
		res.header("Access-Control-Allow-Credentials", false);
		res.header("Access-Control-Max-Age", '86400'); // 24 hours
		res.header("Access-Control-Allow-Headers", "X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept");
	}
	/* 	if (req.method === 'OPTIONS') {
      console.log('!OPTIONS');
      var headers = {};
      // IE8 does not allow domains to be specified, just the *
      // headers["Access-Control-Allow-Origin"] = req.headers.origin;
      //headers["Access-Control-Allow-Origin"] = "*";
      headers["Access-Control-Allow-Methods"] = "POST, GET, PUT, DELETE, OPTIONS";
      headers["Access-Control-Allow-Credentials"] = false;
      headers["Access-Control-Max-Age"] = '86400'; // 24 hours
      headers["Access-Control-Allow-Headers"] = "X-Requested-With, X-HTTP-Method-Override, Content-Type, Accept";
      res.writeHead(200, headers);
      res.end();
	} */
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
				success: function(css){
					res.send(css);
				},
				error: function(){
					res.send('Something went wrong!');
				}
			});
		} else {
			res.send('Something went wrong!');
		}
	});
});

/*
router.get('/:file.css', function(req, res) {
	//res.setHeader('content-type', 'text/css');
	res.header("Content-type", "text/css");
	fs.readFile('/var/zpanel/hostdata/zadmin/public_html/hxcmusic_com/production/css/'+req.params.file+'.css', 'utf-8', function(err, data) {
		if (err) {
			res.send('FATAL An error occurred trying to read in the file: ' + err);
		}
		if (data) {
			res.send(data);
		} else {
			res.send('File ' + req.params.file + ' not found');
		}
	});
});
*/
router.get('/logout.json', function(req, res) {
	if (client.exists('userId')) {
		client.del("userId");
	}
	res.json({
		success: 'true'
	});
});

router.get('/favorites.json', function(req, res) {
	res.send('test');
/* 	if (!client.exists('userId')) {
		res.json({
			error: 'unauthorized'
		});
	} else {
		client.get("userId", function(err, reply) {
			pool.getConnection(function(err, connection) {
				connection.query('SELECT * FROM favorites WHERE user_id = "' + reply + '" ', function(err, rows, fields) {
					if (err) {
						throw err;
					}
					if (rows.length > 0) {
						res.json(rows);

					} else {
						client.del("userId");
						res.json({
							error: 'unauthorized'
						});
					}
					connection.release();
					//client.quit();
				});
			});
		});
	} */
});

router.post('/favorites.json', function(req, res) {
	if (!client.exists('userId')) {
		res.json({
			error: 'unauthorized'
		});
	} else {
		client.get("userId", function(err, reply) {
			pool.getConnection(function(err, connection) {
				var sql = 'INSERT INTO favorites (`bitrate`,`name`,`song_id`,`playtime`,`user_id`) VALUES ("' + req.body.bitrate + '","' + req.body.name + '","' + req.body.song_id + '","' + req.body.playtime + '","' + reply + '")';
				connection.query(sql, function(err, rows, fields) {
					if (err) {
						throw err;
					}
					res.json({
						success: true
					});
					connection.release();
					//client.quit();
				});
			});
		});
	}
});

router.get('/session.json', function(req, res) {
	//res.send('userid: ' + req.session.userId);
	var results;
	if (client.exists('userId')) {
		client.get("userId", function(err, reply) {
			pool.getConnection(function(err, connection) {
				connection.query('SELECT * FROM users WHERE userid = "' + reply + '" LIMIT 1', function(err, rows, fields) {
					if (err) {
						throw err;
					}
					if (typeof rows[0] !== 'undefined') {
						results = {
							username: rows[0].username,
							userId: rows[0].userid,
							userLevel: rows[0].userlevel,
							isLoggedIn: true,
							email: rows[0].email,
							totalDownloads: rows[0].downloads,
							dailyDownloads: rows[0].dailydownloads,
							downloadLimit: rows[0].userlevel === 5 ? null : 3,
							ip: req.headers['x-forwarded-for'],
							image: rows[0].image
						};
						res.json(results);
						//client.quit();
					} else {
						client.del("userId");
						results = {
							username: null,
							userId: null,
							userLevel: 0,
							isLoggedIn: false,
							email: null,
							totalDownloads: null,
							dailyDownloads: 0,
							downloadLimit: 0,
							ip: null,
							image: null
						};
						res.json(results);
					}
					connection.release();
				});
			});
		});
	} else {
		var results = {
			username: null,
			userId: null,
			userLevel: 0,
			isLoggedIn: false,
			email: null,
			totalDownloads: null,
			dailyDownloads: 0,
			downloadLimit: 0,
			ip: null,
			image: null
		};
		res.json(results);
	}
});

router.post('/session.json', function(req, res) {
	var email = req.body.email,
		password = req.body.password;

	var hash = crypto.createHash('md5').update(password).digest('hex');

	pool.getConnection(function(err, connection) {
		connection.query('SELECT * FROM users WHERE email = "' + email + '" AND password = "' + hash + '" ', function(err, rows, fields) {
			if (err) {
				throw err;
			}
			//req.session.userId = rows[0].userid;
			if (rows.length > 0) {
				client.set("userId", rows[0].userid);
				res.json(rows);
			} else {
				res.json({
					error: 'incorrect'
				});
			}
			connection.release();
			//client.quit();
		});
	});
});

router.put('/session.json', function(req, res) {
	var ip = req.headers['x-forwarded-for'],
		totalDownloads = req.body.totalDownloads,
		dailyDownloads = req.body.dailyDownloads,
		image = req.body.image;

	if (!client.exists('userId')) {
		res.json({
			error: 'unauthorized'
		});
	} else {
		client.get("userId", function(err, reply) {
			pool.getConnection(function(err, connection) {
				connection.query('UPDATE users SET dailydownloads = "' + dailyDownloads + '", ip = "' + ip + '", downloads = "' + totalDownloads + '", image = "' + image + '" WHERE userid = "' + reply + '"', function(err, rows, fields) {
					if (err) {
						throw err;
					}
					res.json({
						success: true
					});
					connection.release();
					//client.quit();
				});
			});
		});
	}
});

// more routes for our API will happen here

// REGISTER OUR ROUTES -------------------------------
// all of our routes will be prefixed with /api
app.use('/api', router);

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

/* var users = {};
var numUsers = 0;
global.io.on('connection', function(socket){
	console.log('a user connected!');
	var addedUser = false;
	socket.on('test', function () {
		console.log('yay!!!!!!!!!!!!!!!!');
	});
// when the client emits 'add user', this listens and executes
  socket.on('add user', function (ip) {
	  console.log('adding user ' + ip);
    // we store the username in the socket session for this client
    socket.ip = ip;
    // add the client's username to the global list
    users[ip] = 'online';
    ++numUsers;
    addedUser = true;
    // echo globally (all clients) that a person has connected
	  console.log('triggering userjoined');
    socket.broadcast.emit('user joined', {
      user: socket.ip,
      numUsers: numUsers
    });
  });
	//console.log('user connected to socket', socket);
}); */

// START THE SERVER
// =============================================================================
//app.listen(port);
http.listen(port, function() {
	console.log('listening on *: ' + port);
});
//console.log('Magic happens on port ' + port);