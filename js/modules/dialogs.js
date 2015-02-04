(function(App) {
	'use strict';
	App.module("Dialog", {
		initialize: function(moduleName, app, options) {
			//Add app shortcuts
			//I.E. App.Dialog.Login
			var key;
			for (key in options) {
				if (key !== 'initialize') {
					this[key] = options[key];
				}
			}
		},
		showModal: function(modal) {
			//setTimeout(function(){
				modal.show();
			//}, 250);
		},

		hideModal: function(modal) {
			//modal.messi.removeClass('messi-open');
			//modal.messi.removeClass('puffIn');
			modal.messi.animo({animation: "vanishOut", keep: false, duration: 0.45}, function() {
				modal.hide();
			});
		},
		
		DownloadAlbum: function(e){
			e.preventDefault();
			var me = this,
				artist = $(e.target).data('artist'),
				album = $(e.target).data('album'),
				image = $(e.target).attr('src');
			if (App.User.isLoggedIn && App.User.userLevel > 1) {
				App.Modal.AlbumDownload = new Messi(_.template($('#albumdownload-tpl').html())({
					artist: artist,
					album: album,
					image: image
				}), {
					modal: true,
					modalOpacity: 0.3,
					show: false,
					title: album,
					titleClass: 'anim',
					buttons: [{
						id: 0,
						label: 'Cancel',
						val: 'cancel',
						class: 'cancel-button'
					}, {
						id: 1,
						label: 'Download',
						val: 'download',
						class: 'download-modal-button'
					}]
				});

				App.Dialog.showModal(App.Modal.AlbumDownload);
				App.Modal.AlbumDownload.messi.find('.btnbox').unbind('click');
				App.Modal.AlbumDownload.resize();

				$.ajax({
					url: App.baseURL + '/api/albumTracks.php?album='+album+'&artist='+artist
				}).done(function(data){
					data = JSON.parse(data);
					var tracks = '';
					for(var i = 0, len = data.length; i < len; ++i){
						tracks += (i + 1) + '. ' +data[i].title + '<br>';
					}
					$('.tracks').html(tracks);
				});
				
				App.Util.waitTillReady('.download-modal-button', function() {
					$('.download-modal-button').one('click', function() {
						var modalHeight = $('.albumdownload-wrapper').outerHeight();
						$('.albumdownload-wrapper').css('height', modalHeight + 'px').html('<br><br><center><img src="' + App.baseURL + '/images/loader-white.gif"></center>');
						
						var dir, files;
						$('.messi-actions').hide();
						$(this).parents('.btnbox').remove();
						$('.cancel-button').text('Close');
						$.ajax({
							url: App.baseURL + '/api/downloadAlbum.php?artist='+artist+'&album='+album
						}).done(function(data){
							data = JSON.parse(data);
							dir = data.dir;
							files = data.files;
							var checkProgress = function(){
								setTimeout(function(){
									$.ajax({
										method: 'GET',
										url: App.baseURL + '/api/albumDownloadProgress.php',
										data: {
											dir: dir,
											files: files,
											artist: artist,
											album: album
										}
									}).done(function(data){
										data = JSON.parse(data);
										if(data.status === 'downloading'){
											//$('.albumdownload-wrapper').html('Downloading ' + data.progressText);
											checkProgress();
										}
										if(data.status === 'finished'){
											$('.messi-actions').show();
											$('.albumdownload-wrapper').html('<center><img src="' + App.baseURL + '/images/notification_done.png"><br><br><a href="' + App.apiURL + '/' + data.url + '" data-bypass="true">Download Album Archive</a></center>');
										}
									});
								}, 3000);
							};
							checkProgress();
						});
					});

					$('.cancel-button').one('click', function(e) {
						$('.download-modal-button').unbind('click');
						App.Dialog.hideModal(App.Modal.AlbumDownload);
					});
				});
			} else if (!App.User.isLoggedIn) {
				App.Dialog.SignUp('Please Sign-Up To Download');
			} else if (App.User.isLoggedIn && App.User.userLevel < 5) {
				App.Dialog.Upgrade('userlevel');
			}
		},
		
		SignUp: function(error) {
			if (!error) {
				error = 'Sign-Up';
			}
			var me = this;
			App.Modal.Signup = new Messi(_.template($('#signup-tpl').html()), {
				modal: true,
				modalOpacity: 0.3,
				show: false,
				title: error,
				titleClass: 'anim',
				buttons: [{
					id: 0,
					label: 'Cancel',
					val: 'cancel',
					class: 'cancel-button'
				}, {
					id: 1,
					label: 'Sign-Up',
					val: 'signup',
					class: 'signup-button'
				}]
			});

			App.Dialog.showModal(App.Modal.Signup);

			setTimeout(function(){
				Recaptcha.create("6LdPXPUSAAAAAGS_OydiIAIrLccVlqPavbIqf5N0", $('#recaptcha_div').get(0), {
					theme: "clean",
					callback: function() {
						App.Modal.Signup.resize();
						Recaptcha.focus_response_field();
					}
				});
			}, 1000);

			App.Modal.Signup.messi.find('.btnbox').unbind('click');
			$(document).on('click', '.googlesignin', function(e){
				e.preventDefault();
				App.googleAPI.login();
			});
			$(document).on('click', '.signup-button', function(e) {
				App.vent.trigger('message', 'Please Wait', 'Creating your account...');
				$.ajax({
					type: "POST",
					url: App.apiURL + "/signup.php",
					data: {
						username: $('input[name="username"]').val(),
						email: $('input[name="email"]').val(),
						image: null,
						password: $('input[name="password"]').val(),
						recaptcha_challenge_field: $('[name="recaptcha_challenge_field"]').val(),
						recaptcha_response_field: $('[name="recaptcha_response_field"]').val()
					}
				}).done(function(data) {
					data = JSON.parse(data);
					if(typeof data.error !== 'undefined'){
						App.vent.trigger('message', 'Error', data.error.text);
						Recaptcha.create("6LdPXPUSAAAAAGS_OydiIAIrLccVlqPavbIqf5N0", $('#recaptcha_div').get(0), {
							theme: "clean",
							callback: function() {
								Recaptcha.focus_response_field();
							}
						});
						$('.messi:visible').animo({animation: "shake", keep: false, duration: 0.8});
					}else{
						App.Dialog.hideModal(App.Modal.Signup);
						_gaq.push(['_trackEvent', "User Events", "Registrations", data.email]);
						App.Model.User.set({
							id: Number(data.id),
							dailyDownloads: Number(data.dailyDownloads),
							downloadLimit: Number(data.userLevel) === 5 ? null : 3,
							email: data.email,
							ip: data.ip,
							isLoggedIn: true,
							totalDownloads: Number(data.totalDownloads),
							userId: data.userId,
							userLevel: Number(data.userLevel),
							username: data.username,
							image: data.image
						});

						App.vent.trigger('ui:loggedin');
						App.Router.navigate('upgrade', {
							trigger: true
						});
						App.vent.trigger('message', '', 'Signed Up Successfully!');
					}
				});
			});

			$(document).on('click', '.cancel-button', function(e) {
				App.Dialog.hideModal(App.Modal.Signup);
				$(document).off('click', '.signup-button');
			});
		},
		VideoDownload: function(e) {
			var me = this,
				reachedLimit = App.User.userLevel > 1 ? false : (Number(App.User.downloadLimit) <= Number(App.User.dailyDownloads));
			if (App.User.isLoggedIn && !reachedLimit) {
				var videoId = $(e.target).parents('a').data('video-id'),
					videoTitle = $(e.target).parents('a').data('video-title'),
					videoImage = $(e.target).parents('a').data('video-image'),
					videoDesc = $(e.target).parents('a').data('video-desc'),
					downloadUrl = 'http://'+document.location.host+'/converter/index.php?url=http://www.youtube.com/watch?v=' + videoId + '&type=video';

				App.Modal.VideoDownload = new Messi(_.template($('#videodownload-tpl').html())({
					videoId: videoId,
					videoTitle: videoTitle,
					videoImage: videoImage,
					videoDesc: videoDesc
				}), {
					modal: true,
					modalOpacity: 0.3,
					show: false,
					title: videoTitle,
					titleClass: 'anim',
					buttons: [{
						id: 0,
						label: 'Cancel',
						val: 'cancel',
						class: 'cancel-button'
					}, {
						id: 1,
						label: 'Download',
						val: 'download',
						class: 'download-modal-button'
					}]
				});

				App.Dialog.showModal(App.Modal.VideoDownload);
				App.Modal.VideoDownload.messi.find('.btnbox').unbind('click');
				App.Modal.VideoDownload.resize();

				App.Util.waitTillReady('.download-modal-button', function() {
					$('.download-modal-button').one('click', function() {
						$(this).remove();
						$('.cancel-button').text('Close');
						$('.videodownload-wrapper').html('<iframe src="' + downloadUrl + '"></iframe>');
						$('.videodownload-wrapper iframe').on('load', function() {
							var contentHeight = $(this).get(0).contentWindow.document.body.scrollHeight,
								contentWidth = $(this).get(0).contentWindow.document.body.scrollWidth;
							$('.videodownload-wrapper, .videodownload-wrapper iframe').animate({
								height: contentHeight
							}, 500, function(){
								App.Modal.VideoDownload.resize();
							});
						});
						var daily = Number(App.Model.User.get('dailyDownloads')) + 1,
							total = Number(App.Model.User.get('totalDownloads')) + 1;
						App.Model.User.save({
							'dailyDownloads': daily,
							'totalDownloads': total,
							'lastDownloaded': moment().unix(),
							'ip': App.clientIP
						});
						
						if (App.User.userLevel < 5) {
							setTimeout(function() {
								App.vent.trigger('show:downloadsLeft');
							}, 3000);
						}
					});

					$('.cancel-button').one('click', function(e) {
						$('.download-modal-button').unbind('click');
						App.Dialog.hideModal(App.Modal.VideoDownload);
					});
				});
			} else if (!App.User.isLoggedIn) {
				App.Dialog.SignUp('Please Sign-Up To Download');
			} else if (App.User.isLoggedIn && reachedLimit) {
				App.Dialog.Upgrade();
			}
		},
		Upgrade: function(type) {
			var me = this,
				isLimit = typeof type === 'undefined' ? true : false;
			App.Modal.Upgrade = new Messi(_.template($('#upgradeprompt-tpl').html())(isLimit), {
				modal: true,
				modalOpacity: 0.3,
				show: false,
				titleClass: 'anim',
				title: isLimit ? 'Download Limit Reached' : 'Premium Feature',
				buttons: [{
					id: 0,
					label: 'Cancel',
					val: 'cancel',
					class: 'cancel-button'
				}, {
					id: 1,
					label: 'Upgrade',
					val: 'upgrade',
					class: 'upgrade-modal-button'
				}]
			});
			App.Dialog.showModal(App.Modal.Upgrade);
			App.Util.waitTillReady('.upgrade-modal-button', function() {
				App.Modal.Upgrade.messi.find('.btnbox').unbind('click');
				$('.cancel-button').one('click', function(e) {
					$('.download-modal-button').unbind('click');
					App.Dialog.hideModal(App.Modal.Upgrade);
				});
				$('.upgrade-modal-button').one('click', function(e) {
					$('.upgrade-modal-button').unbind('click');
					App.Dialog.hideModal(App.Modal.Upgrade);
					App.Router.navigate('upgrade', {
						trigger: true
					});
				});
			});
		},
		Download: function(e) {
			if (e) {
				e.preventDefault();
			}
			var me = this,
				reachedLimit = App.User.userLevel > 1 ? false : (Number(App.User.downloadLimit) <= Number(App.User.dailyDownloads));
			if (App.User.isLoggedIn && !reachedLimit) {
				var songId = $(e.target).parents('a').data('song-id'),
					songTitle = $(e.target).parents('a').data('song-title'),
					playtime = $(e.target).parents('a').data('playtime'),
					fetchType = App.Model.User.get('userLevel') === 1 ? 'fetchit' : 'fetch',
					downloadUrl = 'http://' + document.location.host + '/' + fetchType + '/' + songId + '.mp3';
				App.Modal.Download = new Messi(_.template($('#download-tpl').html()), {
					modal: true,
					modalOpacity: 0.3,
					show: false,
					title: songTitle,
					titleClass: 'anim',
					buttons: [{
						id: 0,
						label: 'Cancel',
						val: 'cancel',
						class: 'cancel-button'
					}, {
						id: 1,
						label: 'Download',
						val: 'download',
						class: 'download-modal-button'
					}]
				});

				App.Search.fetchAlbumInfo(songTitle, function(data) {
					App.Dialog.showModal(App.Modal.Download);
					App.Modal.Download.messi.find('.btnbox').unbind('click');
					$('.download-wrapper').html(_.template($('#albuminfo-tpl').html())(data[0]));
					App.Modal.Download.resize();
				});

				App.Search.fetchBitrate(songId, playtime, function(data) {
					if (!data.error && data) {
						App.Modal.Download.messi.find('.playtime').html(data.kbps + ' Kbps - ' + data.size + 'MB');
					} else {
						App.Modal.Download.messi.find('.playtime').html('Unknown');
					}
				});

				App.Util.waitTillReady('.download-modal-button', function() {
					$('.messi-content:visible').css('height', $('.messi-content:visible').height());
					$('.download-modal-button').one('click', function() {
						$('.download-modal-button').parents('.btnbox').css({'opacity': 0.7, 'pointer-events':'none'});
						var launchDownload = function(){
							var downloadIcon = '<span class="jGrowl-download-icon icon-uniE600"></span>';
							App.vent.trigger('message', '', downloadIcon + ' Download will start in a moment...', {timeout:5000});
							var daily = Number(App.Model.User.get('dailyDownloads')) + 1,
								total = Number(App.Model.User.get('totalDownloads')) + 1;
							App.Model.User.save({
								'dailyDownloads': daily,
								'totalDownloads': total,
								'lastDownloaded': moment().unix(),
								'ip': App.clientIP
							}).done(function(data){
								document.location.href = downloadUrl;
								if (App.User.userLevel < 5) {
									setTimeout(function() {
										App.vent.trigger('show:downloadsLeft');
									}, 3000);
								}
							});
							App.Dialog.hideModal(App.Modal.Download);
						};
						
						if(App.User.userLevel < 5){
							var wait = 30;
							$('.download-wrapper').html(_.template($('#countdown-tpl').html()));
							var countDown = function(){
								App.Modal.Download.countDown = setTimeout(function(){
									--wait;
									$('.countdown-wrapper').find('.count-down').html(wait);
									if(wait === 0){
										launchDownload();
									}else{
										countDown();
									}
								}, 1000);
							};
							countDown();
							//$('.messi-content:visible').css('height', 'auto');
							$('.messi-content:visible').animate({
								height: $('.download-wrapper').height()
							}, 500, function(){
								//App.Modal.Download.resize();
							});
						}else{
							launchDownload();
						}
					});

					$('.cancel-button').one('click', function(e) {
						$('.download-modal-button').unbind('click');
						App.Dialog.hideModal(App.Modal.Download);
						if(App.Modal.Download.countDown){
							clearTimeout(App.Modal.Download.countDown);
						}
					});
				});
			} else if (!App.User.isLoggedIn) {
				App.Dialog.SignUp('Please Sign-Up To Download');
			} else if (App.User.isLoggedIn && reachedLimit) {
				App.Dialog.Upgrade();
			}
		},
		Login: function(e) {
			if (e) {
				e.preventDefault();
			}
			if (typeof App.Modal.Login !== 'undefined') {
				App.Modal.Login.show();
				App.Modal.Login.messi.addClass('messi-open');
				return;
			}
			var me = this;
			App.Modal.Login = new Messi(_.template($('#login-tpl').html()), {
				modal: true,
				modalOpacity: 0.3,
				show: false,
				title: 'Member Login',
				titleClass: 'anim',
				buttons: [{
					id: 0,
					label: 'Cancel',
					val: 'cancel',
					class: 'cancel-button'
				}, {
					id: 1,
					label: 'Login',
					val: 'login',
					class: 'login-button'
				}]
			});

			App.Dialog.showModal(App.Modal.Login);

			App.Modal.Login.messi.find('.btnbox').unbind('click');
			$(document).on('click', '.googlesignin', function(e){
				e.preventDefault();
				App.googleAPI.login();
			});
			$(document).on('click', '.login-button', function(e) {
				var isEmail = $('input[name="email"]').val().indexOf('@') > -1,
					params = {},
					loginAttr = isEmail ? 'email' : 'username';
					params[loginAttr] = $('input[name="email"]').val();
					params.password = $('input[name="password"]').val();
				
				$.ajax({
					type: "POST",
					url: App.apiURL + "/login.php",
					data: params
				}).done(function(data) {
					data = JSON.parse(data);
					//data  = data[0];
					App.Modal.Login.hide();
					if (typeof data.userId !== 'undefined') {
						_gaq.push(['_trackEvent', "User Events", "Logins", data.email]);
						App.Model.User.set({
							id: Number(data.id),
							dailyDownloads: Number(data.dailyDownloads),
							downloadLimit: Number(data.userLevel) === 5 ? null : 3,
							email: data.email,
							ip: data.ip,
							isLoggedIn: true,
							totalDownloads: Number(data.totalDownloads),
							userId: data.userId,
							userLevel: Number(data.userLevel),
							username: data.username,
							image: data.image,
							isGoogle: data.isGoogle ? data.isGoogle : false
						});

						App.vent.trigger('ui:loggedin');
						//App.vent.trigger('message', 'HXC Message Center', 'Welcome Back, ' + App.User.username + '!');
						App.vent.trigger('message', '', 'Welcome Back!');
					} else {
						//login error
						App.vent.trigger('message', 'Error', 'Login Details Incorrect');
					}
				});
			});

			$(document).on('click', '.cancel-button', function(e) {
				App.Dialog.hideModal(App.Modal.Login);
			});
		}
	});
})(window.App);