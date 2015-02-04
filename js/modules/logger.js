(function(App) {
	'use strict';
	App.module("Logger", {
		initialize: function(moduleName, app, options) {
			//Add app shortcuts
			var key;
			for (key in options) {
				if (key !== 'initialize') {
					this[key] = options[key];
				}
			}
		},
		
		startLogging: function(){
			window.onerror = function(message, url, linenumber, colno, error) {
				//clean up url we only want the script name
				//delete everything after .js
				var n = url.indexOf('.js');
				url = url.substring(0, n !== -1 ? (n + 3) : url.length);

				//get last folder name and script name
				var parts = url.split('/'),
					result = parts[parts.length - 2] + '/' + parts[parts.length - 1],
					errorMessage = [];
				errorMessage.push(result + ':' + linenumber);
				errorMessage.push(message);
				
				url = url.replace('http://'+document.location.host+'/', '', url);
				_gaq.push(['_trackEvent', "Errors", "Javascript Exceptions", url + "(" + linenumber + "): " + message]);
				$.ajax({
					type: 'POST',
					url: '/notifyOfErrors.php',
					data: {
						m: result + ':' + linenumber + '\n' + message
					}
				});
			};
			
			$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
				var statusText;
				if (jqXHR.status !== 200 && !App.Util.contains(ajaxSettings.url, 'notifyOfErrors')) {
					statusText = jqXHR.statusText;
					var url = ajaxSettings.url,
						n = url.indexOf('.js');
					
					url = url.substring(0, n !== -1 ? (n + 3) : url.length);

					//get last folder name and script name
					var parts = url.split('/'),
						result = parts[parts.length - 2] + '/' + parts[parts.length - 1],
						errorMessage = [];
					
					result = url.replace('http://' + document.location.host + '/', '', result);
					errorMessage.push(result);
					errorMessage.push(thrownError);
					_gaq.push(['_trackEvent', "Errors", "Ajax Error - ", result + ": " + thrownError]);
					$.ajax({
						type: 'POST',
						url: '/notifyOfErrors.php',
						data: {
							m: result + '\n' + thrownError
						}
					});
					App.vent.trigger('message', 'Error', 'Unable to complete request.<br>We have been notified of the problem.');
				}
			});
		}
	});
})(window.App);