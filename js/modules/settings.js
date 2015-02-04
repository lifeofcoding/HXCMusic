(function(App) {
	'use strict';
	App.module("Settings", {
		initialize: function(moduleName, app, options) {
			//Add app shortcuts
			//I.E. App.websiteTitle, App.webRoot, ...
			var key;
			for (key in options) {
				if (key !== 'initialize') {
					if(key === 'baseURL'){
						App[key] = options[key]();
					}else{
						App[key] = options[key];
					}
					//App[key] = options[key];

					/*
						Allow App.ettings access instead
						of App.Settings.option.webRoot
					*/
					if(key === 'baseURL'){
						this[key] = options[key]();
					}else{
						this[key] = options[key];
					}
				}
			}
			this.setupEnvironment();
		},
		startTimeoutTimer: function(){
			var idleTime = 0,
			timerIncrement =  function() {
				idleTime = idleTime + 1;
				if (idleTime > 10) { // 5 minutes
					App.vent.trigger('lockscreen', 'show');
				}
			};
			
			//Increment the idle time counter every minute.
			var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

			//Zero the idle timer on mouse movement.
			$(this).mousemove(function (e) {
				idleTime = 0;
			});
			$(this).keypress(function (e) {
				idleTime = 0;
			});
		},
		
		setupEnvironment: function() {
			if(typeof window.environment === 'undefined'){
				var url = document.location.href;
				App.isProduction = !(url.indexOf('playground') > -1 || url.indexOf('development') > -1 || url.indexOf('?debug=1') > -1);
				App.isDevelopment = (url.indexOf('playground') > -1 || url.indexOf('development') > -1 || url.indexOf('?debug=1') > -1);

				App.Environment = App.isProduction ? 'production' : 'development';
			}else{
				App.Environment = window.environment;
				App.isProduction = App.Environment === 'production' ? true : false;
				App.isDevelopment = App.Environment === 'development' ? true : false;
			}
			if(App.isProduction){
				//App.Logger.startLogging();
			}
		},
		
		apiURL: 'http://api.hxcmusic.com',
		downloadServer: window.downloadServer,
		stripTrailingSlash: function(str) {
			if (str.substr(-1) === '/') {
				return str.substr(0, str.length - 1);
			}
			return str;
		},
		websiteTitle: document.location.host === 'hxcmusic.com' ? 'HXCMusic.com' : 'HXCMusic.me',
		webRoot: '/',
		baseURL: function() {
			return this.getBaseURL(document.location.href) + this.webRoot.substring(1);
		},
		getBaseURL: function(url) {
			var base = url.match(/^http[s]?:\/\/([a-zA-Z0-9-]*\.*)*[a-zA-Z0-9-]+(?!\.[a-zA-Z0-9-]{2,3})\/*/);
			if (base) {
				return this.stripTrailingSlash(base[0]);
			} else {
				return "";
			}
		}
	});
})(window.App);