(function(App) {
	'use strict';
	var routes = Marionette.Controller.extend({

		initialize: function(options) {},

		videoResults: function(search) {
			var exec = function() {
				App.resultsType = 'videos';
				$('label[for="toggle-videos"]').click();
				App.Router.startSearch(search);
			};
			if (App.isReady) {
				exec();
			} else {
				App.vent.on('onReady', function() {
					exec();
				});
			}
		},

		musicResults: function(search) {
			var exec = function() {
				App.resultsType = 'music';
				$('label[for="toggle-music"]').click();
				App.Router.startSearch(search);
			};
			if (App.isReady) {
				exec();
			} else {
				App.vent.on('onReady', function() {
					exec();
				});
			}
		},

		music: function() {
			App.Util.afterPageRender(function(){
				App.Router.currentController = new App.Controller.Music();
			});
		},

		videos: function() {
			this.videoResults('music');
		},

		account: function() {
			App.Util.afterPageRender(function(){
				App.Router.currentController = new App.Controller.Account();
			});
		},

		forums: function() {
			App.Util.afterPageRender(function(){
				App.Router.currentController = new App.Controller.Forums();
			});
		},

		events: function() {
			App.Util.afterPageRender(function(){
				App.Router.currentController = new App.Controller.Events();
			});
		},

		video: function(videoId, title) {
			App.Util.afterPageRender(function(){
				//App.Router.currentController = new App.Controller.Video();
				App.vent.trigger('page:video', videoId, title);
			});
		},

		upgrade: function() {
			App.Util.afterPageRender(function(){
				App.Router.currentController = new App.Controller.Upgrade();
			});
		},

		favorites: function() {
			App.Util.afterPageRender(function(){
				App.vent.trigger('show:content', new App.View.Favorites({
					collection:App.Model.Favorites
				}));
			});
		},

		index: function() {
			App.Util.afterPageRender(function(){
				App.Router.currentController = new App.Controller.Index();
			});
		},
		
		discover: function(){
			App.vent.trigger('loading:start');
			App.Util.afterPageRender(function(){
				$(document).ready(function() {
					$.ajax({
						url: App.baseURL + "/api/suggest.php",
						//url: App.apiURL + "/suggestions",
						beforeSend: function(xhr) {
							//xhr.overrideMimeType("text/plain; charset=x-user-defined");
						}
					}).done(function(data) {
						App.Suggested = JSON.parse(data);

						App.vent.trigger('loading:end');
						App.vent.trigger('hidePlayAll');
						App.Router.setPageTitle('Discover');
						App.Router.setPageHeader('Discover');
						App.Router.setPageSubTitle('Personalized Music Suggestions');
						App.vent.trigger('show:content', new App.View.Discover());
					});
				});
				//App.vent.trigger('show:content', new App.View.Discover());
			});
		}

	});
	App.Controller.Routes = routes;
})(window.App);