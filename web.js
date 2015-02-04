// web.js
/* Custom version of http://backbonetutorials.com/seo-for-single-page-apps/ */
var fs = require('fs');
var path = require('path');
var fullPath = __dirname + '/';
//var fullPath = '/var/zpanel/hostdata/zadmin/public_html/hxcmusic_com/production/';


	// Express is our web server that can handle request
	var express = require('/usr/local/lib/node_modules/express'),
		app = express();

	var getContent = function(url, callback) {
		var content = '';
		// Here we spawn a phantom.js process, the first element of the 
		// array is our phantomjs script and the second element is our url 
		url = url.replace(':3000', '');
		//url = url.replace('api.', '');
		var subdomain = url.replace('http://','').split('.')[0]; //Get subdomain
		url = url.replace(subdomain + '.', ''); //Remove subdomain
		//console.log(url);
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
		var url = req.protocol + '://' + req.get('host') + req.originalUrl,
			filename = url.replace(/^.*(\\|\/|\:)/, '');
		if(filename === 'status.json'){
			fs.stat(path.resolve(fullPath, 'web.js'), function(err, stats){
				if(err){
					res.send('Error Occurred');
				}else{
					res.json({status:'success', version:0.12, modified: stats.mtime});
				}
			});
		}else{
			getContent(url, function(content) {
				res.send(content);
			});
		}
	};

	app.get(/(.*)/, respond);
	app.listen(3000);

	//console.log('Worker ' + cluster.worker.id + ' running!');

