(function(App) {
	'use strict';
	var _this;

	var viewport = Backbone.Marionette.LayoutView.extend({
		template: '#viewport-tpl',

		id: 'viewport',

		ui: {
			pageHeader: '.explore_header',
			loader: '#loader',
			signInLink: '#sign_in_link',
			playlistButton: '#playlist_button',
			playlist: '#current_playlist',
			closePlaylist: '#current_playlist_close',
			queue: '#current_playlist_rows',
			pageSubTitle: '.explore_description',
			playAllButton: '#explore_play_button_sotd',
			pageHeaderWrapper: '.explore_header_wrapper',
			headerNavMusic: '#explore_nav_music',
			headerNavVideos: '#explore_nav_videos',
			headerNavRadio: '#explore_nav_radio',
			headerNavEvents: '#explore_nav_events',
			headerNavForums: '#explore_nav_forums',
			headerNavDiscover: '#explore_nav_discover',
			userNav: '#user_nav',
			songStream: '#song-stream',
			userCount: '#user-count',
			topSeatchForm: '#top_search_form',
			homepageSearchForm: '#homepage_search_form',
			userNavRadioButton: '#left_row_radio',
			signUpLink: '#create_account_link',
			searchTypeToggle: 'input[name="search-type"]',
			searchInput: '.search_query',
			signoutLink: '#sign_out_link',
			supportButton: '#left_row_support',
			accountLink: '#account_link',
			bottomAdWrapper: '#bottom_ad_wrapper'
		},

		regions: {
			Header: '#header',
			Content: '#explore_content_sotd',
			Player: '#bottom',
			Homepage: '#homepage',
			Queue: '#current_playlist',
			Footer: '#footer',
			UserNav: '#user_nav',
			contentWindow: '#right'
		},

		events: {
			'click @ui.signInLink': 'showLoginDialog',
			'submit @ui.topSeatchForm': 'doSearch',
			'submit @ui.homepageSearchForm': 'doHomepageSearch',
			'click @ui.playAllButton': 'addAllToQueue',
			'click @ui.userNavRadioButton': 'startRadio',
			'click @ui.headerNavRadio': 'startRadio',
			'click @ui.signUpLink': 'signUp',
			'change @ui.searchTypeToggle': 'toggleType',
			'click @ui.signoutLink': 'logout',
			'click @ui.supportButton': 'showSupport',
			'click @ui.accountLink': 'showAccount'
		},

		initialize: function() {
			_this = this;

			// Application events
			App.vent.on('search:results', _.bind(this.showResults, this));

			App.vent.on('loading:start', _.bind(this.showLoading, this));

			App.vent.on('loading:end', _.bind(this.hideLoading, this));

			App.vent.on('login:dialog', _.bind(this.showLoginDialog, this));

			App.vent.on('page:video', _.bind(this.streamVideo, this));

			App.vent.on('update:queue', _.bind(this.updateQueue, this));

			App.vent.on('show:homepage', _.bind(this.showHomepage, this));

			App.vent.on('toggle:menu', _.bind(this.toggleNavMenu, this));

			App.vent.on('loading:error', _.bind(this.showError, this));

			App.vent.on('message', _.bind(this.showMessage, this));

			App.vent.on('ui:loggedin', _.bind(this.updateUILoggedIn, this));

			App.vent.on('update', _.bind(this.showUpdateMessage, this));

			App.vent.on('initialize:tooltip', _.bind(this.initializeToolTip, this));

			App.vent.on('setPageTitle', _.bind(this.setPageTitle, this));

			App.vent.on('setPageHeader', _.bind(this.setPageHeader, this));

			App.vent.on('setPageSubTitle', _.bind(this.setPageSubTitle, this));

			App.vent.on('showPlayAll', _.bind(this.showPlayAll, this));
			
			App.vent.on('hidePlayAll', _.bind(this.hidePlayAll, this));
			
			App.vent.on('show:downloadsLeft', _.bind(this.showDownloadsLeft, this));
			
			App.vent.on('show:content', _.bind(this.showContent, this));
			
			App.vent.on('update:image', _.bind(this.updateImage, this));
			
			App.vent.on('lockscreen', _.bind(this.lockscreen, this));

			$(document).on('load', '#ad-frame', function() {
				console.log('making links in iframe');
				$('#ad-frame a').attr('target', '_blank');
			});

			/* Overwrite messi show function so we can let css3 handle opacity transition */
			Messi.prototype.show = function() {
				if (this.visible) {
					return;
				}
				$('#modal-wrapper').show();
				if (this.options.modal && this.modal !== null) {
					this.modal.show();
				}
				this.messi.appendTo('#modal-wrapper');

				if (this.options.center) {
					this.options.viewport = this.viewport(jQuery('.messi-box', this.messi));
				}
/* 				this.messi.css({
					top: this.options.viewport.top,
					left: this.options.viewport.left,
					'z-index': this.options.zIndex + jQuery('.messi').length
				}).show().animate({
					opacity: 1
				}, 300); */
/* 				var modal = this.messi,
					_this = this;
				modal.resize();
				modal.css({'z-index':_this.options.zIndex + jQuery('.messi').length, opacity:1});
				modal.animo({animation: "magictime jelly", keep: false, duration: 1}, function() {
					
				}); */
				//this.messi.addClass('jelly').css({'z-index':this.options.zIndex + jQuery('.messi').length, opacity:1}).show();
				//this.messi.addClass('magictime puffIn').css({'z-index':this.options.zIndex + jQuery('.messi').length, opacity:1}).show();
				var modal = this.messi,
					_this = this;
				
				modal.resize();
				modal.animo({animation: "magictime vanishIn", keep: false, duration: 0.45}, function() {
					modal.css({'z-index':_this.options.zIndex + jQuery('.messi').length, opacity:1}).show();
					modal.addClass('messi-open');
					$('#wrapper').animo({animation: "blur", keep: false, duration: 0.45});
				}).show();
				
				
				this.visible = true;
			};

			Messi.prototype.hide = function(after) {
				if (!this.visible) {
					return;
				}
				var _this = this;

				//this.messi.animo({animation: "magictime puffOut", keep: false, duration: 0.3}, function() {
				$('#wrapper').removeClass('blur');
					$('#modal-wrapper').hide();
					if (_this.options.modal && _this.modal !== null) {
						_this.modal.remove();
					}
					_this.messi.css({
						display: 'none'
					}).remove();
					_this.visible = false;
					if (after) {
						after.call();
					}
					if (_this.options.unload) {
						_this.unload();
					}
				//});
/* 				this.messi.animate({
					opacity: 0
				}, 200, function() {
					$('#modal-wrapper').hide();
					if (_this.options.modal && _this.modal !== null) {
						_this.modal.remove();
					}
					_this.messi.css({
						display: 'none'
					}).remove();
					_this.visible = false;
					if (after) {
						after.call();
					}
					if (_this.options.unload) {
						_this.unload();
					}
				}); */
/* 				debugger;
				this.messi.animo({animation: "magictime puffOut", keep: false, duration: 0.3}, function() {
					debugger;
					$('#modal-wrapper').hide();
					if (_this.options.modal && _this.modal !== null) {
						_this.modal.remove();
					}
					_this.messi.css({
						display: 'none'
					}).remove();
					_this.visible = false;
					if (after) {
						after.call();
					}
					if (_this.options.unload) {
						_this.unload();
					}
				}); */
				
/* 				this.messi.addClass('magictime puffOut'); */

				return this;
			};
			//Auto update view when music model changes
			//_.bindAll(this, 'render'); // bind 'this' in 'render'
			//App.Model.Music = new App.Model.Music();
			//App.Model.Music.bind('change', this.render);
			
			App.Regions = this.getRegions();
			App.setBackground = this.setBackground;
		},
		
		startRadio: function(e){
			App.Controller.Player.radio.apply(this, arguments);
		},
		
		showAccount: function(e){
			//App.googleAPI.login();
		},

		showSupport: function(e) {
			e.preventDefault();
			UserVoice.push(['set', {
				// Options can also be set on specific widgets instead of globally
				mode: 'contact',
				target: false, // 'none' for toaster popups, #id for a specific element on the page
				position: 'automatic', // Popover position
				height: '425px', // Widget height
				width: '400px', // Widget width
				accent_color: '#367dca', // Widget accent color
				locale: 'en', // Defaults to your account’s localization
				// forum_id: '209871', // Defaults to your account’s default forum
				// smartvote_status_ids: [403280,403282], // Defaults to server-side settings
				// smartvote_category_ids: [69243], // Defaults to all categories
				ticket_custom_fields: {
					'Username': App.Model.User.get('username'),
					'Email': App.Model.User.get('email')
				},
				contact_title: 'Having any problems? We are here to help!',
				smartvote_title: 'What should we do next?',
				contact_enabled: true,
				screenshot_enabled: true,
				smartvote_enabled: true,
				post_idea_enabled: true
			}]);
			UserVoice.showLightbox();
		},

		toggleType: function(e) {
			if ($(this.ui.searchTypeToggle.selector + ':checked').val() === 'music') {
				App.resultsType = 'music';
				this.ui.searchInput.val('Search for music...');
			} else {
				App.resultsType = 'videos';
				this.ui.searchInput.val('Search for videos...');
			}
		},

		showUpdateMessage: function() {
			this.showMessage('', 'New Update Available! Click here to reload.', {
				sticky: true,
				timeout: 1000,
				link: document.location.href,
				onClick: function() {
					$(this).find('.jGrowl-message').text('Reloading page...');
				}
			});
		},

		streamVideo: function(videoId, title) {
			title = title.replace('---', '-');
			App.currentVideo = videoId;
			this.Content.show(new App.View.Video());
			this.setPageTitle(title);
			this.setPageHeader(title);
			this.setPageSubTitle('');
			this.hidePlayAll();
			App.Util.waitTillReady('#video_player', function() {
				$(this).mediaelementplayer({
					startVolume: 0.9,
					alwaysShowControls: true,
					videoVolume: 'horizontal',
					features: ['playpause', 'progress', 'volume', 'fullscreen'],
					success: function(mediaElement, domObject) {
						// add event listener
						mediaElement.addEventListener('volumechange', function(e) {
							//alert(e.target.volume);
							//$.cookie("video_volume", e.target.volume, {expires : 1, path : '/'});

						}, false);

						mediaElement.setVolume(0.9);
					},
					error: function() {
						App.vent.trigger('message', 'Error', 'Unable to play video.');
					}
				});


			});
		},
		
		lockscreen: function(action){
			if(action === 'show'){
				$('.lockscreen-bg').html(_.template($('#lockscreen-tpl').html())()).fadeIn('slow');

				$('html').addClass('lockscreen');

				/*  */
				var startTime = function()
				{
					var today = new Date();
					var h = today.getHours();
					var m = today.getMinutes();
					var s = today.getSeconds();

					// add a zero in front of numbers<10
					m = checkTime(m);
					s = checkTime(s);

					//Check for PM and AM
					var day_or_night = (h > 11) ? "PM" : "AM";

					//Convert to 12 hours system
					if (h > 12){
						h -= 12;
					}
					//Add time to the headline and update every 500 milliseconds
					$('#time').html(h + ":" + m + ":" + s + " " + day_or_night);
					setTimeout(function() {
						startTime();
					}, 500);
				};

				var checkTime = function(i)
				{
					if (i < 10)
					{
						i = "0" + i;
					}
					return i;
				};

				/* CENTER ELEMENTS IN THE SCREEN */
				jQuery.fn.center = function() {
					this.css("position", "absolute");
					this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
							$(window).scrollTop()) - 30 + "px");
					this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
							$(window).scrollLeft()) + "px");
					return this;
				};

				startTime();
				$(".center").center();
				$(window).resize(function() {
					$(".center").center();
				});

				var login = function(e){
					e.preventDefault();
					if($('.lockscreen-credentials input').val()){
						var isEmail = false,
							params = {};
							params.username = App.Model.User.get('username');
							params.password = $('.lockscreen-credentials input').val();

						$.ajax({
							type: "POST",
							url: App.apiURL + "/login.php",
							data: params
						}).done(function(data) {
							data = JSON.parse(data);
							if (typeof data.userId !== 'undefined') {
								App.vent.trigger('lockscreen', 'hide');
							} else {
								//login error
								App.vent.trigger('message', 'Error', 'Login Details Incorrect');
							}
						});
					}else{
						App.vent.trigger('message', 'Error', 'Password cannot be blank!');
					}
				};
				$('.lockscreen-credentials input').on('focus blur', function(e){
					if(e.type === 'focus'){
						$('.lockscreen-credentials input').on('keydown', function(e){
							if(e.keyCode === 13){
								login(e);
							}
						});
					}else if(e.type === 'blur'){
						$('.lockscreen-credentials input').unbind('keydown');
					}
				});
				$('#lockscreen-login').on('click', function(e){
					login(e);
				});
			}else{
				$('#lockscreen-login').unbind('click');
				$('.lockscreen-bg').fadeOut();
			}
		},

		showPlayAll: function() {
			this.ui.pageHeaderWrapper.css('padding-left', '110px');
			this.ui.playAllButton.show();
		},

		hidePlayAll: function() {
			this.ui.pageHeaderWrapper.css('padding-left', '20px');
			this.ui.playAllButton.hide();
		},

		showHomepage: function() {
			this.Homepage.show(new App.View.Homepage());
		},

		updateQueue: function() {
			this.Queue.show(new App.View.Queue());
		},

		addAllToQueue: function() {
			if (App.currentPage !== 'searchVideos') {
				var collection = App.currentPage === 'showFavorites' ? App.Model.Favorites : App.Model.Music,
				    actionText = App.currentPage === 'showFavorites' ? 'favorites' : 'results';
				if (collection.models.length > 0) {
					//add each model to queue collection 
					var songModels = [];
					for(var i = 0, len = collection.models.length; i < len; ++i){
						//clone so it does not try to do rest calls because the original models have a url property
						songModels.push(collection.models[i].clone());
					}
					
					App.Model.Queue.add(songModels);
					
					App.vent.trigger('update:queue');
					App.vent.trigger('message', 'HXC Message Center', 'All ' + actionText + ' added to queue.');
					if (!App.Player.isPlaying) {
						$($('.play-song').get(0)).click();
					}

				}
			}
		},

		updateImage: function() {
			this.UserNav.show(new App.View.UserNav());
		},

		logout: function(e) {
			e.preventDefault();
			$.removeCookie('HXCID');
			document.location.reload();
		},

		toggleNavMenu: function(page) {
			var me = this;
			var menuItem = false;
			if (page.toLowerCase().indexOf('music') > -1) {
				menuItem = me.ui.headerNavMusic;
			} else if (page.toLowerCase().indexOf('events') > -1) {
				menuItem = me.ui.headerNavEvents;
			} else if (page.toLowerCase().indexOf('videos') > -1) {
				menuItem = me.ui.headerNavVideos;
			} else if (page.toLowerCase().indexOf('forums') > -1) {
				menuItem = me.ui.headerNavForums;
			} else if (page.toLowerCase().indexOf('discover') > -1) {
				menuItem = me.ui.headerNavDiscover;
			} else {
				$('.explore_nav_item').each(function() {
					$(this).removeClass('active');
				});
			}

			if (menuItem) {
				menuItem.addClass('active');
				menuItem.siblings('.explore_nav_item').removeClass('active');
			}
		},
		
		displayAd: function(){
			if(App.currentPage !== 'upgrade'){
				App.Ads.bottom();
			}
		},
		
		showContent: function(view){
			this.Content.show(view);
			this.displayAd();
		},

		showLoginDialog: function(e) {
			App.Dialog.Login.apply(this, arguments);
		},

		updateUILoggedIn: function() {
			var me = this;
			var updateUI = function() {
				$.cookie('HXCID', App.User.id);
				App.Model.Favorites = new App.Model.Favorites();
				$('#top_right').html('<a href="' + App.baseURL + '/profile" id="account_link" class="account_link">My Account</a> <a href="#" id="sign_out_link" data-bypass="true" class="account_link">Logout</a>');
				me.UserNav.show(new App.View.UserNav());
				UserVoice.push(['identify', {
					email: App.Model.User.get('email'), // User’s email address
					account: {
						id: App.Model.User.get('userId'), // Optional: associate multiple users with a single account
						username: App.Model.User.get('username'), // Account name
						plan: App.Model.User.get('username') === 5 ? 'Premium' : 'Free' // Plan name for the account
					}
				}]);
				App.Settings.startTimeoutTimer();
			};
			if (App.isReady) {
				updateUI();
			} else {
				App.vent.on('onReady', function() {
					updateUI();
				});
			}
		},

		signUp: function(e) {
			if (e) {
				e.preventDefault();
			}
			this.showSignUpDialog();
		},

		showSignUpDialog: function(error) {
			App.Dialog.SignUp.apply(this, arguments);
		},

		showUpgradeDialog: function() {
			App.Dialog.Upgrade.apply(this, arguments);
		},

		displayHomepage: function() {
			$('#explore').css('visibility', 'hidden');
			$('#explore_nav').css('opacity', 'hidden');
			$('#homepage').show();
		},

		hideHomepage: function() {
			App.vent.trigger('close:homepage');
		},

		updateURL: function(url) {
			Backbone.history.navigate(url);
		},

		showMessage: function(title, message, options) {
			//jGrowl - https://github.com/stanlemon/jGrowl
			var sticky = false,
				timeout = 3000,
				position = '#playerrightmsg';
			if (typeof options !== 'undefined') {
				if (typeof options.sticky !== 'undefined') {
					sticky = options.sticky;
				}
				if (typeof options.timeout !== 'undefined') {
					timeout = options.timeout;
				}
				if (typeof options.position !== 'undefined') {
					position = options.position === 'left' ? '#playerleftmsg' : '#playerrightmsg';
				}
			}
			$(position).jGrowl(message, {
				header: title,
				sticky: sticky,
				life: timeout,
				afterOpen: function() {
					$(this).css('opacity', 0.95);
					if (typeof options !== 'undefined' && typeof options.link !== 'undefined') {
						$(this).css({
							cursor: 'pointer'
						});
						$(this).on('click', function() {
							if (typeof options.onClick !== 'undefined') {
								options.onClick.apply(this, arguments);
							}
							if (options.link.indexOf('http') > -1) {
								document.location.href = options.link;
							} else {
								App.Router.navigate(options.link, {
									trigger: true,
									replace: false
								});
							}
						});
					}
				},
				openDuration: 200,
				closeDuration: 200,
				open: function(){
					var me = $(this);
					me.animo({ animation: 'vanishIn', duration: 0.3, keep: false });
				},
				beforeClose: function(){
					var me = $(this);
					me.animo({ animation: 'vanishOut', duration: 0.3, keep: false }, function(){
						//debugger;
						me.hide();
					});
				},
				beforeOpen: function() {
					var me = $(this);
					if (title === '') {
						$(this).find('.jGrowl-message').css('margin-top', '11px');
					}
				},
				closeTemplate: '' //Overwrite close button template for custom css
			});
		},

		showDownloadsLeft: function() {
			var downloadsLeft = App.Model.User.get('downloadLimit') - App.Model.User.get('dailyDownloads');
			if (downloadsLeft > 0) {
				App.vent.trigger('message', '', downloadsLeft + ' ' + (downloadsLeft === 1 ? 'download' : 'downloads') + ' left for today');
			} else {
				var warningImage = '<img src="' + App.baseURL + '/images/download-warning.png" class="download-warning">';
				App.vent.trigger('message', '', warningImage + 'No more downloads left for today', {
					sticky: true,
					link: '/upgrade',
					onClick: function() {
						$(this).fadeOut('normal', function() {
							$(this).remove();
						});
					}
				});
			}
		},

		showError: function() {
			App.vent.trigger('loading:end');
			this.setPageTitle('Loading Error');
			this.setPageHeader('Loading Error');
			this.setPageSubTitle('');
			this.hidePlayAll();
			App.vent.trigger('message', 'Error', 'Unable to load page...');

			var errorWrapper = '<div class="error-wrapper">';
			errorWrapper += '<div class="error-message">Oops... Looks like we are having some trouble.</div>';
			errorWrapper += '<br>We have been notified of the problem. Please wait a few minutes and try again.</div>';
			$('#explore_content_sotd').html(errorWrapper);
		},

		scrollToTop: function(callback) {
			$('#right').animate({
				scrollTop: 0
			}).promise().then(function() {
				if(typeof callback !== undefined && typeof callback === function){
					callback();
				}
			});
		},

		showLoading: function(title) {
			if (!title) {
				title = 'Loading...';
			}
			App.isLoading = true;
			this.scrollToTop();
			App.loadingCountDown = setTimeout(function() {
				if ($('#spinner').is(':visible')) {
					App.vent.trigger('loading:error');
					$(document.body).css({
						cursor: 'auto'
					});
					$('#spinner').fadeOut();
				}
			}, 15000);
			$(document.body).css({
				cursor: 'progress'
			});

			this.setPageTitle(App.Util.capitalizeWords(title));
			this.setPageHeader('Loading...');
			this.setPageSubTitle('Fetching content...');
			$('#spinner').fadeIn();
			$('#middle').addClass('blur');
		},

		hideLoading: function() {
			App.isLoading = false;
			$(document.body).css({
				cursor: 'auto'
			});
			clearTimeout(App.loadingCountDown);
			App.loadingCountDown = false;

			$('#spinner').fadeOut();
			$('#middle').removeClass('blur');
		},
		
		setBackground: function(url, options){
			if(typeof options === 'undefined' || typeof options !== 'object'){
				options = {fade: 1000, centeredX: false, centeredY: true};
			}
			//create background same size as browser window
			//url = App.baseURL + '/imagecache/image.php?src='+url.replace(App.baseURL,'')+'&w='+$(window).width()+'&h='+$(window).height()+'&zc=1';
			
			//create background same size as #middle area of website
			url = App.baseURL + '/imagecache/image.php?src='+url.replace(App.baseURL,'')+'&w='+$('#middle').width()+'&h='+$('#middle').height()+'&zc=1';
			$('#middle').backstretch(url, options);
		},
		
		doSearch: function(event) {
			event.preventDefault();
			App.resultsPage = 1;
			this.hideHomepage();
			App.vent.trigger('search');
		},

		doHomepageSearch: function(event) {
			event.preventDefault();
			App.resultsPage = 1;
			this.hideHomepage();
			var search = $('#homepage_search_input').val();
			$('#top_search_input').val(search);
			App.vent.trigger('search');
		},

		onShow: function() {
			this.Header.show(new App.View.Header());
			this.Footer.show(new App.View.Footer());
			this.Player.show(new App.View.Player());
			this.Queue.show(new App.View.Queue());

			//Used to fix any ui elements that were not rendered yet when ui object was created
			this.fixUiElements();
			App.vent.trigger('initialize:tooltip');
			
			//this.ui.bottomAd.html(App.Settings.displayAd());
			//this.Content.currentView.$el.append('<div id="bottom_ad">'+App.Settings.displayAd()+'</div>');
			
			/* Large background Advertisements */
			/*
			$('#explore_sotd').css({cursor:'pointer'}).on('click', function(e){
				if(e.target.id === 'explore_sotd'){
					//show buy add dialog
					var mAlert = Messi.alert('<span id="msg">Put your large background ad here!<br>Contact us at <a href="mailto:lifeofcoding@gmail.com">lifeofcoding@gmail.com</a> for more information.</span>');
					$('.messi').addClass('messi-open');
					$('.messi-content').css({
						height: '100px',
						'text-align': 'center'
					});
					$('.messi-content').find('#msg').css({
						'margin-top': '38px',
						display: 'inline-block'
					});
					mAlert.resize();
					App.Util.waitTillReady('.messi-open', function() {
						$('.messi-open:visible').css('top','25%');
					});
				}
			});
			$('.explore_section_wrapper').css({cursor:'auto'});
			*/
		},

		/* Used to fix any ui elements that were not rendered yet when ui object was created */
		fixUiElements: function() {
			for (var key in this.ui) {
				if (this.ui[key].length === 0) {
					this.ui[key] = $(this.ui[key].selector);
				}
			}
		},

		setPageHeader: function(title) {
			this.ui.pageHeader.text(title);
		},

		setPageSubTitle: function(title) {
			this.ui.pageSubTitle.text(title);
		},

		setPageTitle: function(title) {
			$('title').html(App.Util.capitalizeWords(title) + ' at ' + App.websiteTitle + '&trade;');
		},

		initializeToolTip: function() {
			$('.tooltip').poshytip({
				className: 'tip-twitter',
				liveEvents: true,
				showTimeout: 1,
				alignTo: 'target',
				alignX: 'center',
				offsetY: 5,
				allowTipHover: false,
				fade: false,
				slide: false
			});
		},

		showResults: function() {
			App.vent.trigger('loading:end');
			var searchParams = App.Search.options.getParams(),
				searchTerms = searchParams.search,
				pageTitle;
			if (App.currentPage !== 'videos') {
				App.currentPage = App.resultsType === 'music' ? 'searchMusic' : 'searchVideos';
			}
			this.toggleNavMenu(App.currentPage);
			
			if (App.resultsType !== 'videos') {
				this.showPlayAll();
				pageTitle = App.Util.capitalizeWords(searchTerms) + ' ' + App.Util.capitalizeWords(App.resultsType) + ' Downloads';
			}else{
				this.hidePlayAll();
				pageTitle = App.Util.capitalizeWords(searchTerms) + ' Video Downloads';
			}
			if (App.currentPage === 'videos') {
				this.updateURL('/videos');
			}else{
				this.updateURL(App.resultsType + '/' + App.Util.seoFriendlyDashes(searchTerms));
			}
			this.setPageTitle(pageTitle);
			this.setPageHeader('Search Results');
			this.setPageSubTitle(pageTitle);
			this.Content.show(new App.View.Results());

			var validateImages = $(".song_row_cover_art .validateImg");
			validateImages.on('error', function() {
				$(this).parents('.song_row_cover_art').css({
					'background': 'url(' + App.baseURL + '/images/question-mark.png)',
					'background-size': '114px 114px',
					'background-position-x': '-19px',
					'background-position-y': '-9px'
				}).children('.validateImg').remove();
			});
			$.each(validateImages, function(){
				$(this).attr('src', $(this).data('validate-url'));
			});
			
			this.displayAd();
		}
	});

	App.View.Viewport = viewport;
})(window.App);
