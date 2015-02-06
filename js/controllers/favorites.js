(function(App) {
	'use strict';
	var favorites = Marionette.Controller.extend({
		/* Moved from view to act as a proper MVC controller & view */
		ui: {},

		/* Moved from view to act as a proper MVC controller & view */
		events: {
			'click .song_action_queue': 'addToQueue',
			'click .song_action_remove': 'removeFromFavorites',
			'click .download-button': 'showDownloadDialog',
			'click .play-song': 'streamSong'
		},
		
		initialize: function(options) {
			/* Add Methods to view */
			if(typeof options.view !== 'undefined'){
				var key;
				for (key in this) {
					if (typeof options.view[key] === 'undefined') {
						options.view[key] = this[key];
					}
				}
			}
			
			if (App.Router.currentController !== null) {
				App.Router.currentController.destroy();
			}
			if (App.isReady) {
				App.vent.trigger('loading:end');
				App.vent.trigger('showPlayAll');
				App.Router.setPageDetails({
					pageTitle:'My Favorites',
					pageHeader:'My Favorites',
					subTitle:''
				});
				App.vent.trigger('initialize:tooltip');
			}
			
			App.Router.currentController = this;
		},
		
		addToQueue: function(e) {
			App.Controller.Player.addToQueue.apply(this, arguments);
		},
		
		removeFromFavorites: function(e) {
			App.Controller.Player.removeFromFavorites.apply(this, arguments);
		},
		
		removeFromQueue: function(e) {
			App.Controller.Player.removeFromQueue.apply(this, arguments);
		},
		
		showDownloadDialog: function(e) {
			App.Dialog.Download.apply(this, arguments);
		},
		
		streamSong: function(e) {
			App.Controller.Player.streamSong.apply(this, arguments);
		},

		/* onShow does not work here but does in results view */
		onRender: function() {
			//App.vent.trigger('initialize:tooltip');
		},

		onShow: function(){
			App.vent.trigger('initialize:tooltip');
		},

		onClose: function() {

		}

	});
	App.Controller.Favorites = favorites;
})(window.App);
