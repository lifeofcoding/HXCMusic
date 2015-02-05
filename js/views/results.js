(function(App) {
	'use strict';

	var results = Backbone.Marionette.ItemView.extend({
		template: '#results-tpl',
		//className: 'player',

		ui: {
			downloadButton: '.download-button',
			downloadVideoButton: '.download-video-button'
		},

		events: {
			'click .download-button': 'showDownloadDialog',
			'click .download-video-button': 'showDownloadVideoDialog',
			'click .play-song': 'streamSong',
			'click .song_action_love': 'addToFavorites',
			'click .song_action_queue': 'addToQueue',
			'click .download-album': 'downloadAlbum'
		},

		initialize: function() {
			App.vent.on('download', _.bind(this.showDownloadDialog, this));
		},
		
		downloadAlbum: function(e){
			App.Dialog.DownloadAlbum.apply(this, arguments);
		},

		onShow: function() {
			App.vent.trigger('initialize:tooltip');
		},
		
		/* onRender does not work here but does in favorites view */
		onRender: function() {},

		onClose: function() {},
		
		addToQueue: function(e) {
			App.Controller.Player.addToQueue.apply(this, arguments);
		},
		
		addToFavorites: function(e) {
			App.Controller.Player.addToFavorites.apply(this, arguments);
		},
		
		showDownloadDialog: function(e) {
			App.Dialog.Download.apply(this, arguments);
		},
		
		showDownloadVideoDialog: function(e) {
			App.Dialog.VideoDownload.apply(this, arguments);
		},
		
		streamSong: function(e) {
			App.Controller.Player.streamSong.apply(this, arguments);
		},
		
		templateHelpers: {
			count: 0
		},
		
		onBeforeDestroy: function(){
			App.resultsPage = 1;
		}

	});
	App.View.Results = results;
})(window.App);
