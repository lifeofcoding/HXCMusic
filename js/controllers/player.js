(function(App) {
	'use strict';
	var player = Marionette.Controller.extend({

		/* Moved from view to act as a proper MVC controller & view */
		ui: {
			playButton: '#play_button',
			shuffleButton: '#shuffle_button',
			playlistButton: '#playlist_button',
			playlist: '#current_playlist'
		},

		/* Moved from view to act as a proper MVC controller & view */
		events: {
			'click @ui.playButton': 'togglePlay',
			'click #next_button': 'playNext',
			'click #prev_button': 'playPrev',
			'click @ui.shuffleButton': 'toggleShuffle',
			'click @ui.playlistButton': 'togglePlaylist',
			'click #download_button': 'downloadPlaying'
		},

		initialize: function(options){
			/* Add Methods to view */
			if(typeof options.view !== 'undefined'){
				var key;
				for (key in this) {
					if (typeof options.view[key] === 'undefined') {
						options.view[key] = this[key];
					}
				}
			}

			App.vent.on('radio', _.bind(this.radio, this));
			App.vent.on('initialize:player', _.bind(this.initializePlayer, this));

			var me = this;
			App.Util.waitTillReady('#volume_thumb', function() {
				App.Volume = {};
				App.Volume.thumb = '#volume_thumb';
				App.Volume.width = $('#volume_back').width();
				App.Volume.left = $('#volume_back').offset().left + 6;
				App.Volume.right = App.Volume.left + App.Volume.width;
				App.Volume.speaker = '#volume_speaker';
				App.Volume.volume = '1';

				App.Volume.set = function(left) {
					$(App.Volume.thumb).css('left', left);
					if (left <= 0) {
						$(App.Volume.speaker).removeClass('volume_on');
						$(App.Volume.speaker).addClass('volume_off');
					} else {
						$(App.Volume.speaker).removeClass('volume_off');
						$(App.Volume.speaker).addClass('volume_on');
					}
				};

				$('#volume_thumb').on('mousedown', function(e) {
					$(App.Volume.thumb).addClass('volume_thumb_active');
					$(document).on('mousemove', function(e) {
						me.volume.mousemove(e);
					});
					$(document).on('mouseup', function(e) {
						me.volume.mouseup(e);
					});
				});

				$('#volume_back').on('click', function(e) {
					me.volume.mousemove(e);
				});

				if($.cookie('HXCVolume')){
					var volume = JSON.parse($.cookie('HXCVolume'));
					if(typeof App.Player.setVolume !== 'undefined'){
						App.Player.setVolume(volume.num);
					}
					App.Volume.volume = volume.num;
					App.Volume.set(volume.ui);
				}
			});
			//App.baseURL = this.getBaseURL(document.location.href);
			/* Pre-Setup Variables */
			App.playListOpen = false;
			App.Player.isPaused = true;
			App.Player.isPlaying = false;
			App.Player.Radio = this.radio;
			App.Player.shuffle = false;
			App.Player.currentPlaylist = typeof $.cookie('currentPlaylist') !== 'undefined' ? Number($.cookie('currentPlaylist')) : 0;
			App.resultsType = 'music';
		},

/* 		togglePlayButton: function(){
			debugger;
			this.ui.playButton.addClass('paused');
		}, */

		/*setupEvents: function(){
			var _this = this;
			_.each(this.events, function(key, selector){
				if(typeof _this[key] === 'undefined'){
					_this[key] = _this.controller[key];
				}
			});
		},*/

		downloadPlaying: function(e){
			if(typeof $(e.target).data('song-id') !== 'undefined'){
				App.Dialog.Download.apply(this, arguments);
			}
		},

		togglePlaylist: function() {
			var css = {},
				playlist = $('#current_playlist'),
				moveTo = $('#current_playlist div').outerHeight() + $('#bottom').outerHeight();
			css[App.browserPrefix.css + 'transition'] = App.browserPrefix.css + 'transform 1s cubic-bezier(.25,.88,.62,1)';
			if (!App.playListOpen) {
				css[App.browserPrefix.css + 'transform'] = 'translate3d(0, -' + moveTo + 'px, 0)';
			}else{
				css[App.browserPrefix.css + 'transform'] = 'translate3d(0, 0, 0)';
			}
			playlist.css(css).afterTransition(function() {
				if(!App.playListOpen){
					App.playListOpen = true;
				}else{
					App.playListOpen = false;
				}
			});
		},

		toggleShuffle: function(e) {
			e.preventDefault();
			if (this.ui.shuffleButton.hasClass('selected')) {
				this.ui.shuffleButton.removeClass('selected');
				App.Player.shuffle = false;
			} else {
				this.ui.shuffleButton.addClass('selected');
				App.Player.shuffle = true;
			}
		},

		togglePlay: function() {
			if ($('#audio-element').length > 0) {
				if (this.ui.playButton.hasClass('paused')) {
					this.ui.playButton.removeClass('paused');
					if (App.Player.isPlaying) {
						App.Player.pause();
					}
					App.Player.isPaused = true;
				} else {
					this.ui.playButton.addClass('paused');
					if (!App.Player.isPlaying) {
						App.Player.play();
					}
					App.Player.isPaused = false;
				}
			}
		},

		volume: {
			mousemove: function(e) {
				var x = e.clientX;
				if (x < App.Volume.left - 3) {
					x = App.Volume.left - 3;
				}
				if (x > App.Volume.right - 10) {
					x = App.Volume.right - 10;
				}
				var volLeft = x - App.Volume.left;
				App.Volume.set(volLeft);
				App.Volume.volume = volLeft / (App.Volume.width - 10);
				if (typeof App.Player.volume !== 'undefined') {
					App.Player.setVolume(App.Volume.volume < 0 ? 0 : App.Volume.volume);
				}
				$.cookie('HXCVolume', JSON.stringify({num: (App.Volume.volume < 0 ? 0 : App.Volume.volume), ui: volLeft}), { expires: 30, path: '/' });
				if (App.Volume.volume < 0) {
					App.Volume.volume = 0;
				}
				if (App.Volume.volume > 1) {
					App.Volume.volume = 1;
				}
			},
			mouseup: function(e) {
				$(document).unbind('mousemove');
				$(document).unbind('mouseup');
			}
		},

		radio: function(e) {
			var me = this;
			if (typeof e !== 'undefined') {
				e.preventDefault();
			}
			App.vent.trigger('message', 'Please Wait', 'Finding a good song to play for you...');
			$.ajax({
				type: "GET",
				url: App.baseURL + "/api/radio.php"
			}).done(function(data) {
				//data = JSON.parse(data);
				var songId = data.track_id,
					songTitle = data.track_title;
				App.vent.trigger('message', 'Now Playing', songTitle, {
					position: 'left'
				});
				$('.now-playing').show();
				$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');
				App.Controller.Player.initializePlayer();
				$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
				$('#display_coverart').html('<img src="" width="40" height="40" class="display_coverart_glare">').show();
				var image = App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png',
					validateImage = $('#display_coverart img');
				validateImage.on('error', function() {
					validateImage.attr('src', App.baseURL + '/images/question-mark.png');
				});
				validateImage.attr('src', image);
				App.Player.currentSongId = songId;
				App.Player.currentSongTitle = songTitle;
				App.Player.isRadio = true;
				App.Player.play();
				$('#player-download-button').hide();
			});
		},

		playNext: function() {
			var me = this;
			if (typeof App.Player.currentSongId !== 'undefined' && App.Player.currentSongId !== null) {
				App.vent.trigger('localStreamEnded', App.Player.currentSongId);
			}
			if (!App.Player.isRadio && App.Player.isPlaying) {
				App.Player.pause();
				//check if there is a song next in queue
				if (App.Model.Queue.models.length === 0) {
					return;
				}

				var songModel = _.find(App.Model.Queue.models, function(model) {
					return model.attributes.songId === App.Player.currentSongId;
				});
				var songIndex;
				if (typeof songModel !== 'undefined') {
					songIndex = App.Model.Queue.indexOf(songModel);
				} else {
					songIndex = -1;
				}
				var plus = App.Player.shuffle ? App.Util.getRandomInt(0, App.Model.Queue.models.length) : (songIndex + 1);
				if (typeof App.Model.Queue.models[plus] !== 'undefined') {
					var songId = App.Model.Queue.models[plus].get('songId'),
						songTitle = App.Model.Queue.models[plus].get('name');
					$('.now-playing').show();
					$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');

					App.vent.trigger('initialize:player');
					$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
					$('#display_coverart').html('<img src="" width="40" height="40" class="display_coverart_glare">').show();
					var image = App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png',
						validateImage = $('#display_coverart img');
					validateImage.on('error', function() {
						validateImage.attr('src', App.baseURL + '/images/question-mark.png');
					});
					validateImage.attr('src', image);
					App.Player.currentSongId = songId;
					App.Player.currentSongTitle = songTitle;
					App.Player.play();

					$('#player-download-button').show();
					$('#player-download-button div, #player-download-button').data({
						'song-id': songId,
						'song-title': songTitle,
						playtime: App.Model.Queue.models[plus].get('bitrate')
					});

				} else {
					App.vent.trigger('message', '', 'This is the last song!');
				}
			} else if (App.Player.isRadio) {
				App.Player.Radio();
			}
		},

		playPrev: function() {
			var me = this;
			if (typeof App.Player.currentSongId !== 'undefined' && App.Player.currentSongId !== null) {
				App.vent.trigger('localStreamEnded', App.Player.currentSongId);
			}
			if (!App.Player.isRadio && App.Player.isPlaying) {
				App.Player.pause();
				//check if there is a song next in queue
				if (App.Model.Queue.models.length === 0) {
					return;
				}
				var songModel = _.find(App.Model.Queue.models, function(model) {
					return model.attributes.songId === App.Player.currentSongId;
				});
				var songIndex;
				if (typeof songModel !== 'undefined') {
					songIndex = App.Model.Queue.indexOf(songModel);
				} else {
					songIndex = 1;
				}
				var plus = App.Player.shuffle ? App.Util.getRandomInt(0, App.Model.Queue.models.length) : (songIndex - 1);
				if (typeof App.Model.Queue.models[plus] !== 'undefined') {
					var songId = App.Model.Queue.models[plus].get('songId'),
						songTitle = App.Model.Queue.models[plus].get('name'),
						bitrate = App.Model.Queue.models[plus].get('bitrate');
					$('.now-playing').show();
					$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');

					App.vent.trigger('initialize:player');
					$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
					$('#display_coverart').html('<img src="" width="40" height="40" class="display_coverart_glare">').show();
					var image = App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png',
						validateImage = $('#display_coverart img');
					validateImage.on('error', function() {
						validateImage.attr('src', App.baseURL + '/images/question-mark.png');
					});
					validateImage.attr('src', image);
					App.Player.currentSongId = songId;
					App.Player.currentSongTitle = songTitle;
					App.Player.play();

					$('#player-download-button').show();

					$('#player-download-button div, #player-download-button').data({
						'song-id':songId,
						'song-title':songTitle,
                                                playtime: bitrate;
					});
				} else {
					App.vent.trigger('message', '', 'This is the first song!');
				}
			} else if (App.Player.isRadio) {
				App.Player.Radio();
			}
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
					songId: songRecord.get('songId'),
					playtime: songRecord.get('playtime'),
					name: songRecord.get('name'),
					userId: App.User.userId,
					playlistId: App.Player.currentPlaylist
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
			if (App.currentPage === 'favorites') {
				model = App.Model.Favorites;
			} else {
				model = App.Model.Music;
			}
			var songId = $(e.target).data('song-id'),
				songRecord = model.findWhere({
					songId: songId
				});
			var songModel = songRecord.clone();

			App.Model.Queue.add(songModel);
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
			App.Controller.Player.initializePlayer();
			$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
			$('#display_coverart').html('<img src="" width="40" height="40" class="display_coverart_glare">').show();
			var image = App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png',
				validateImage = $('#display_coverart img');
			validateImage.on('error', function() {
				validateImage.attr('src', App.baseURL + '/images/question-mark.png');
			});
			validateImage.attr('src', image);

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
						$('#current_playlist_rows').find('.playing').removeClass('playing');
						$('#current_playlist_rows').find('a[data-song-title="' + App.Player.currentSongTitle + '"]').parents('li').addClass('playing');
					}, false);

					mediaElement.addEventListener('play', function(e) {
						App.Player.isPlaying = true;
						App.Player.isPaused = false;
						$('#play_button').addClass('paused');
					}, false);

					mediaElement.addEventListener('ended', function(e) {
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
								var songId = App.Model.Queue.models[plus].get('songId'),
									songTitle = App.Model.Queue.models[plus].get('name');

								$('.now-playing').show();
								$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');
								App.Controller.Player.initializePlayer();
								$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
								$('#display_coverart').html('<img src="" width="40" height="40" class="display_coverart_glare">').show();
								var image = App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png',
									validateImage = $('#display_coverart img');
								validateImage.on('error', function() {
									validateImage.attr('src', App.baseURL + '/images/question-mark.png');
								});
								validateImage.attr('src', image);
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
			App.Player.shuffle = currentProps.shuffle;
			App.Player.currentPlaylist = currentProps.currentPlaylist;
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
						$('#current_playlist_rows').find('.playing').removeClass('playing');
						$('#current_playlist_rows').find('a[data-song-title="' + App.Player.currentSongTitle + '"]').parents('li').addClass('playing');
					}, false);

					// add event listener
					mediaElement.addEventListener('ended', function(e) {
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
								var songId = App.Model.Queue.models[plus].get('songId'),
									songTitle = App.Model.Queue.models[plus].get('name');
								$('.now-playing').show();
								$('#audio-span').html('<audio src="' + App.baseURL + '/fetch/' + songId + '.mp3" autoplay="autoplay" id="audio-element" width="100%" volume="' + App.Volume.volume + '"></audio>');
								App.Controller.Player.initializePlayer();
								$('.now-playing-artist').text(decodeURI(App.Util.capitalizeWords(songTitle))).show();
								$('#display_coverart').html('<img src="" width="40" height="40" class="display_coverart_glare">').show();
								var image = App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(songTitle)) + '.png',
									validateImage = $('#display_coverart img');
								validateImage.on('error', function() {
									validateImage.attr('src', App.baseURL + '/images/question-mark.png');
								});
								validateImage.attr('src', image);
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
	App.Controller.Player = player;
})(window.App);
