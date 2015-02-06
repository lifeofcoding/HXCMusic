(function(App) {
	'use strict';
	var music = Marionette.Controller.extend({

		initialize: function(options) {
			if (App.Router.currentController !== null) {
				App.Router.currentController.destroy();
			}
			$(document).ready(function() {
				App.vent.trigger('loading:start');
				$.ajax({
					url: App.apiURL + '/music/1',
					beforeSend: function(xhr) {
						$('label[for="toggle-music"]').click();
					}
				}).done(function(data) {
					App.Page.Music = JSON.parse(data);
					App.vent.trigger('loading:end');
					App.Router.setPageDetails({
						pageTitle:'Explore Music',
						pageHeader:'Explore Music',
						subTitle:'Featured Artists'
					});
					App.vent.trigger('hidePlayAll');
					App.vent.trigger('show:content', new App.View.Music());
				});
			});
		}

	});
	App.Controller.Music = music;
})(window.App);
