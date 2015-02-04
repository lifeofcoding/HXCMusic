(function(App) {
	'use strict';
	App.module("Playlist", {
		initialize: function(moduleName, app, options) {
			//Add app shortcuts
			var key;
			for (key in options) {
				if (key !== 'initialize') {
					this[key] = options[key];
				}
			}
			
			App.vent.on('initialize:player', _.bind(this.initializePlayer, this));
		},
		
		addToFavorites: function(e){
			if (App.User.isLoggedIn) {
				var songId = $(e.target).data('song-id'),
					songRecord = App.Model.Music.findWhere({
						id: songId
					});
				var checkFavs = App.Model.Favorites.findWhere({
					songId: songId
				});
				if (typeof checkFavs !== 'undefined') {
					App.vent.trigger('message', 'Error', 'Already added to favorites!');
					return;
				}
				var favModel = new App.Model.Favorite();
				favModel.set({
					bitrate: songRecord.get('bitrate'),
					songId: songRecord.get('id'),
					playtime: songRecord.get('playtime'),
					name: songRecord.get('name'),
					userId: App.User.userId
				});
				App.Model.Favorites.add(favModel);
				favModel.save().done(function() {
					$(e.target).addClass('on');
					App.vent.trigger('message', 'Added To Favorites', App.Util.capitalizeWords(songRecord.get('name')));
				});

			} else {
				App.vent.trigger('message', 'Error', 'You must be logged in to add to favorites.');
			}
		},
		
		addToQueue: function(e) {
			var model;
			if (App.currentPage === 'showFavorites') {
				model = App.Model.Favorites;
			} else {
				model = App.Model.Music;
			}
			var songId = $(e.target).data('song-id'),
				songRecord = model.findWhere({
					songId: songId
				});
			App.Model.Queue.add(songRecord);
			//App.vent.trigger('update:queue');
			App.vent.trigger('message', 'Added To Queue', App.Util.capitalizeWords(songRecord.get('name')));
		},
		
		removeFromFavorites: function(e){
			var model = App.Model.Favorites;

			var songId = $(e.target).data('song-id'),
				songRecord = model.findWhere({
					songId: songId
				});
			$('.tooltip').poshytip('hide');
			songRecord.destroy({url: App.apiURL + '/favorites/' + songRecord.id, success: function(model, response) {
				App.vent.trigger('message', 'Removed From Favorites', App.Util.capitalizeWords(songRecord.get('name')));
			}});
		},
		
		removeFromQueue: function(){
			var model = App.Model.Queue;
			var songId = $(e.target).data('song-id'),
				songRecord = model.findWhere({
					songId: songId
				});
			App.Model.Queue.remove(songRecord);
			//App.vent.trigger('update:queue');
			App.vent.trigger('message', 'Removed From Queue', App.Util.capitalizeWords(songRecord.get('name')));
		},
		
		showDownloadDialog: function(e) {
			App.Dialog.Download.apply(this, arguments);
		},
		
		streamSong: function(e) {
			e.preventDefault();

			if (typeof App.Player.currentSongId !== 'undefined' && App.Player.currentSongId !== null) {
				App.vent.trigger('localStreamEnded', App.Player.currentSongId);
			}
			App.Player.isRadio = false;
			var songId = $(e.target).data('song-id'),
				playtime = $(e.target).data('playtime'),
				songTitle = $(e.target).data('song-title');
			App.vent.trigger('message', 'Now Playing', songTitle, {
				position: 'left'
			});
			$('.now-playing').show();
			$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');
			App.MusicController.initializePlayer();
			$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
			$('#display_coverart').html('<img src="' + App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png" width="40" height="40" class="display_coverart_glare">').show();
			App.Player.currentSongId = songId;
			App.Player.currentSongTitle = songTitle;
			App.Player.play();

			$('#player-download-button').show();
			$('#player-download-button div, #player-download-button').data('song-id', songId);
			$('#player-download-button div, #player-download-button').data('song-title', songTitle);
			$('#player-download-button div, #player-download-button').data('playtime', playtime);
		},
		
		initializePlayer: function() {
			var me = this, currentProps = App.Player;
			App.Player = $('#audio-element').mediaelementplayer({
				startVolume: App.Volume.volume,
				success: function(mediaElement, domObject) {

					// add event listener
					mediaElement.addEventListener('volumechange', function(e) {
						//alert(e.target.volume);
						//$.cookie("volume", e.target.volume, {expires : 1, path : '/'});

					}, false);

					mediaElement.addEventListener('canplay', function(e) {
						App.socket.emit('streamStarted', {
							songTitle: App.Player.currentSongTitle,
							songId: App.Player.currentSongId
						});
						$('#current_playlist_rows').find('a[data-song-title="' + App.Player.currentSongTitle + '"]').parents('li').addClass('.playing');
					}, false);

					mediaElement.addEventListener('play', function(e) {
						App.Player.isPlaying = true;
						App.Player.isPaused = false;
						$('#play_button').addClass('paused');
					}, false);

					mediaElement.addEventListener('ended', function(e) {
						$('#current_playlist_rows').find('.playing').removeClass('.playing');
						App.Player.isPlaying = false;
						App.vent.trigger('localStreamEnded', App.Player.currentSongId);
						if (!App.Player.isRadio) {
							//check if there is a song next in queue
							if (App.Model.Queue.models.length === 0) {
								return;
							}
							var songModel = _.find(App.Model.Queue.models, function(model) {
								return model.attributes.songId === App.Player.currentSongId;
							});
							var songIndex, plus;
							if (typeof songModel !== 'undefined') {
								songIndex = App.Model.Queue.indexOf(songModel);
								plus = App.Player.shuffle ? App.Util.getRandomInt(0, App.Model.Queue.models.length) : (songIndex + 1);
							} else {
								plus = App.Player.shuffle ? App.Util.getRandomInt(0, App.Model.Queue.models.length) : 0;
							}
							if (typeof App.Model.Queue.models[plus] !== 'undefined') {
								var songId = App.Model.Queue.models[plus].get('id'),
									songTitle = App.Model.Queue.models[plus].get('name');

								$('.now-playing').show();
								$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');
								App.MusicController.initializePlayer();
								$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
								$('#display_coverart').html('<img src="' + App.baseURL + '/api/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png" width="40" height="40" class="display_coverart_glare">').show();
								App.Player.currentSongId = songId;
								App.Player.currentSongTitle = songTitle;
								App.Player.play();
							}
						} else {
							App.Player.Radio();
						}
					}, false);

					mediaElement.addEventListener('pause', function(e) {
						App.Player.isPlaying = false;
						App.Player.isPaused = true;
						$('#play_button').removeClass('paused');
					}, false);
					mediaElement.addEventListener('error', function() {
						$.ajax({
							type: "GET",
							url: App.baseURL + '/api/getyoutubeurl.php?id=' + App.Player.currentSongId,
							success: function(url) {
								me.streamYoutubeSong(url);
							}
						});
					}, false);
				}
			});
			//App.Player.Radio = currentProps.radio;
			App.Player = App.Player.get(0);
			App.Player.Radio = currentProps.Radio;
		},
		
		streamYoutubeSong: function(url) {
			var me = this, currentProps = App.Player;
			url = 'https://www.youtube.com/watch?v=' + url;
			var videoElement = '' +
					'<video autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '">' +
					'<source type="video/youtube" src="' + url + '" />' +
					'</video>';
			$('#audio-span').html(videoElement);
			App.Player = $('#audio-element').mediaelementplayer({
				startVolume: App.Volume.volume,
				alwaysShowControls: true,
				videoVolume: 'horizontal',
				features: ['playpause', 'progress', 'volume'],
				success: function(mediaElement, domObject) {
					$('.mejs-layers').css('display', 'none');
					$('.me-plugin').css('visibility', 'hidden');
					$('.mejs-controls').css({top:'0px', 'margin-left':'25px'});

					// add event listener
					mediaElement.addEventListener('volumechange', function(e) {
						//alert(e.target.volume);
						//$.cookie("volume", e.target.volume, {expires : 1, path : '/'});

					}, false);

					mediaElement.addEventListener('canplay', function(e) {
						App.socket.emit('streamStarted', {
							songTitle: App.Player.currentSongTitle,
							songId: App.Player.currentSongId
						});
						$('#current_playlist_rows').find('a[data-song-title="' + App.Player.currentSongTitle + '"]').parents('li').addClass('.playing');
					}, false);

					// add event listener
					mediaElement.addEventListener('ended', function(e) {
						$('#current_playlist_rows').find('.playing').removeClass('.playing');
						App.Player.isPlaying = false;
						App.vent.trigger('localStreamEnded', App.Player.currentSongId);
						if (!App.Player.isRadio) {
							//check if there is a song next in queue
							if (App.Model.Queue.models.length === 0) {
								return;
							}
							var songModel = _.find(App.Model.Queue.models, function(model) {
								return model.attributes.songId === App.Player.currentSongId;
							});

							var songIndex, plus;
							if (typeof songModel !== 'undefined') {
								songIndex = App.Model.Queue.indexOf(songModel);
								plus = App.Player.shuffle ? App.Util.getRandomInt(0, App.Model.Queue.models.length) : (songIndex + 1);
							} else {
								plus = App.Player.shuffle ? App.Util.getRandomInt(0, App.Model.Queue.models.length) : 0;
							}
							if (typeof App.Model.Queue.models[plus] !== 'undefined') {
								var songId = App.Model.Queue.models[plus].get('id'),
									songTitle = App.Model.Queue.models[plus].get('name');
								$('.now-playing').show();
								$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');
								App.MusicController.initializePlayer();
								$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
								$('#display_coverart').html('<img src="' + App.baseURL + '/api/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png" width="40" height="40" class="display_coverart_glare">').show();
								App.Player.currentSongId = songId;
								App.Player.currentSongTitle = songTitle;
								App.Player.play();
							}
						} else {
							App.Player.Radio();
						}
					}, false);

					mediaElement.addEventListener('play', function(e) {
						App.Player.isPlaying = true;
						App.Player.isPaused = false;
						$('#play_button').addClass('paused');
					}, false);

					mediaElement.addEventListener('pause', function(e) {
						App.Player.isPlaying = false;
						App.Player.isPaused = true;
						$('#play_button').removeClass('paused');
					}, false);

					// call the play method
					//mediaElement.play();
				}
			});
			App.Player = App.Player.get(0);
			App.Player.Radio = currentProps.Radio;
		}
	});
})(window.App);