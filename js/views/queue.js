(function(App) {
	'use strict';

	var queue = Backbone.Marionette.ItemView.extend({
		template: '#queue-tpl',
		//className: 'player',
		collection: App.Model.Queue,

		ui: {
			clearButton: '#current_playlist_clear',
			closeButton: '#current_playlist_close',
			removeSong: '.close',
			playlistSelect: '#playlists select'
		},

		
		events: {
			'click @ui.clearButton': 'clearQueue',
			'click @ui.closeButton': 'togglePlaylist',
			'click @ui.removeSong': 'removeSong',
			'click [data-song-id]': 'streamSong',
			'change @ui.playlistSelect': 'selectPlaylist'
		},

		collectionEvents: {
			'reset': 'render',
			'add': 'render',
			'remove': 'render'
		},
		
		bindings: {
			//'.testit': 'title'
		},

		initialize: function() {},

		removeSong: function(e){
			e.preventDefault();
			var songId = $(e.target).siblings('a[data-song-id]').data('songId'),
				songModel = this.collection.findWhere({songId: songId});
			this.collection.remove(songModel);
		},

		togglePlaylist: function() {
			App.vent.trigger('toggle:playlist');
		},

		clearQueue: function() {
			var collection = this.collection;
			_.invoke(collection.models, function(){
				collection.sync('delete', this);
			});
			App.Model.Queue.reset();
		},
		
		streamSong: function(e) {
			App.Controller.Player.streamSong.apply(this, arguments);
		},
		
		selectPlaylist: function(e){
			var me = $(e.target),
				_this = this,
				value = me.val();
			if(value === 'new'){
				me.replaceWith('<input type="text" id="playlist-name"><a href="#" id="save-playlist">Save</a>');
				$('#save-playlist').one('click', function(){
					App.Model.Playlists.add({title:$('#playlist-name').val(), userId:App.User.userId}).save().done(function(model){
						App.Model.Playlists.fetch().done(function(){
							var currentPlaylist = App.Model.Playlists.get(0).id;
							$.cookie('currentPlaylist', currentPlaylist, { expires: 7, path: '/' });
							App.Regions.Queue.currentView.render();
						});
					});
				});
			}
		},

		onShow: function() {}
	});
	App.View.Queue = queue;
})(window.App);
