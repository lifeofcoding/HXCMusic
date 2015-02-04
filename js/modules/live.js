(function(App) {
	'use strict';
	App.module("Live", {
		initialize: function(moduleName, app, options) {
			//Add app shortcuts
			//I.E. App.websiteTitle, App.webRoot, ...
			var key;
			for (key in options) {
				if (key !== 'initialize') {
					this[key] = options[key];
				}
			}
		},
		/* Triggered automatically once app starts (initialize is to soon when required variables are undefined) */
		start: function(moduleName, app, options) {
			var _this = this;
			if (App.Model.User.get('ip') !== '178.62.169.191') {
				var socket = io.connect('http://' + document.location.host + ':8080/');
				socket.on('connect', function() {
					_this.connect({
						ip: App.Model.User.get('ip'),
						socket: socket
					});
				});
				socket.on('beingStreamed', function(data) {
					var beingStreamed = data.beingStreamed;
					for (var i = 0, len = beingStreamed.length; i < len; ++i) {
						_this.songStreaming(beingStreamed[i]);
					}
				});
				socket.on('addUser', function(data) {
					_this.addUser(data);
				});
				socket.on('songStreaming', function(data) {
					_this.songStreaming(data);
				});
				socket.on('streamEnded', function(data) {
					_this.streamEnded(data.songId);
				});
			}
		},
		connect: function(data) {
			App.socket = data.socket;
			App.socket.emit('newUser', data.ip);
		},

		addUser: function(data) {
			App.usersOnline = data.online;
			App.viewport.currentView.ui.userCount.html(App.usersOnline);
			console.log('New User connected: ' + data.ip);
		},

		songStreaming: function(data) {
			if (data.songId !== App.Player.currentSongId) {
				var checkStreams = _.find(App.songsStreaming, function(record) {
					return record.songId === data.songId;
				});
				if (typeof checkStreams === 'undefined') {
					console.log('new song streaming: ' + data.songTitle + ' total: ' + data.totalPlaying);
					App.songsStreaming.push(data);
					var template = _.template($('#songstream-tpl').html())(data);
					App.viewport.currentView.ui.songStream.append(template);
					var image = App.apiURL + '/albumcover/' + App.Util.seoFriendlyDashes(encodeURI(data.songTitle)) + '.png',
						validateImage = $('.stream-wrapper[data-song-id="' + data.songId + '"]').find('img');
					validateImage.on('error', function() {
						validateImage.attr('src', App.baseURL + '/images/music_note_404.png');
					});
					validateImage.attr('src', image);
					if (App.songsStreaming.length === 1) {
						App.viewport.currentView.ui.songStream.find('#currently-streaming').show();
						$($('.stream-wrapper').get(0)).css('margin-top', '5px');
					}
					$('.stream-wrapper:hidden').animo({
						animation: 'bounceInUp',
						duration: 1
					}, function() {}).show();
				}
			}
		},

		localStreamEnded: function(songId) {
			App.socket.emit('streamEnded', songId);
		},

		streamEnded: function(songId) {
			if (songId !== App.Player.currentSongId) {
				var record = _.findWhere(App.songsStreaming, {
					songId: songId
				});
				if (typeof record !== 'undefined') {
					var index = App.songsStreaming.indexOf(record);
					App.songsStreaming.splice(index, 1);

					$($('.stream-wrapper').get(App.songsStreaming.length - 1)).animo({
						animation: 'lightSpeedOut',
						duration: 1
					}, function() {
						$('.stream-wrapper[data-song-id="' + songId + '"]').remove();
						if (App.songsStreaming.length === 0) {
							App.viewport.currentView.ui.songStream.find('#currently-streaming').hide();
						}
					});
				}
			}
		}
	});
})(window.App);