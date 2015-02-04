// web.js
/* Custom version of http://backbonetutorials.com/seo-for-single-page-apps/ */

var cluster = require('cluster'), // Include the cluster module
	fullPath = '/var/zpanel/hostdata/zadmin/production/';

// Code to run if we're in the master process
if (cluster.isMaster) {

	// Count the machine's CPUs
	var cpuCount = require('os').cpus().length;
	cpuCount = 1; //manual override since it spawns the phantomjs processes anyways
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
} else {
	// Express is our web server that can handle request
	var express = require('/usr/local/lib/node_modules/express'),
		app = express();

	var getContent = function(url, callback) {
		var content = '';
		// Here we spawn a phantom.js process, the first element of the 
		// array is our phantomjs script and the second element is our url 
		url = url.replace(':3000', '');
		url = url.replace('stats.', '');
		//url = 'http://hxcmusic.com/music';
		//var phantom = require('child_process').spawn('node_modules/phantomjs/bin/phantomjs', ['phantom-server.js', url]);
		var phantom = require('child_process').spawn(fullPath + 'node_modules/phantomjs/bin/phantomjs', [fullPath + 'phantom-server.js', url]);
		phantom.stdout.setEncoding('utf8');
		// Our phantom.js script is simply logging the output and
		// we access it here through stdout
		phantom.stdout.on('data', function(data) {
			content += data.toString();
		});
		phantom.on('exit', function(code) {
			if (code !== 0) {
				console.log('We have an error - Code: ', code);
			} else {
				// once our phantom.js script exits, let's call out call back
				// which outputs the contents to the page
				callback(content);
			}
		});
	};

	var respond = function(req, res) {
		// Because we use [P] in htaccess we have access to this header
		//url = 'http://' + req.headers['x-forwarded-host'] + req.params[0];
		var url = req.protocol + '://' + req.get('host') + req.originalUrl;
		getContent(url, function(content) {
			res.send(content);
		});
	};

	app.get(/(.*)/, respond);
	app.listen(3000);

	console.log('Worker ' + cluster.worker.id + ' running!');

}