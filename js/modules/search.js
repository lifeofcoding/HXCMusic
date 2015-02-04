(function(App) {
	'use strict';
	App.module("Search", {
		initialize: function(moduleName, app, options) {
			App.vent.on('search', _.bind(options.fetchResults, options.getParams()));

			App.resultsType = 'music';
			//Add app shortcuts
			//I.E. App.Search.showBio ...
			var key;
			for (key in options) {
				if (key !== 'initialize') {
					this[key] = options[key];
				}
			}
		},

		_moveTimer: null,

		initializeAlbumsContainer: function() {
			var _this = this,
				scollWidth,
				canScroll,
				hovering = false,
				albumsWrapper = $('#albums-wrapper'),
				scrolled = 0,
				css = {},
				prefix = App.browserPrefix.css,
				albumsTable = $('#albums-wrapper table');
			if (albumsWrapper.width() < albumsTable.width()) {
				scollWidth = albumsTable.width() - albumsWrapper.width();
				$('.scroll-indicator').hover(
					function(e) {
						hovering = true;
						var moveContainer = function(direction, duration) {
							if(direction === 'left'){
								canScroll = scrolled < 0;
								_this._moveTimer = setTimeout(function() {
									if(canScroll){
										scrolled = scrolled + albumsWrapper.width();
										css[prefix + 'transform'] = 'translate3d('+scrolled+'px, 0, 0)';
										albumsTable.css(css).afterTransition(function() {
											if(hovering){
												moveContainer(direction, 2000);
											}
										});
									}
								}, duration);
							}
							if(direction === 'right'){
								canScroll = scollWidth >= Math.abs(scrolled);
								_this._moveTimer = setTimeout(function() {
									if(canScroll){
										scrolled = scrolled - albumsWrapper.width();
										css[prefix + 'transform'] = 'translate3d('+scrolled+'px, 0, 0)';
										albumsTable.css(css).afterTransition(function() {
											if(hovering){
												moveContainer(direction, 2000);
											}
										});
									}
								}, duration);
							}
						};
						moveContainer($(this).data('direction'), 100);
					}, function() {
						clearTimeout(_this._moveTimer);
						hovering = false;
					}
				);
			}else{
				$('.scroll-indicator').hide();
			}
		},
		
		_loadingNext: false,
		
		_maxPages: 5,
		
		nextPage: function(){
			var _this = this,
				params = App.Search.options.getParams(),
				model, collection, url;
			
			if(!this._loadingNext && this._maxPages > params.page){
				App.resultsPage = (App.resultsPage + 1);
				_this._loadingNext = true;

				if(App.resultsType === 'music'){
					collection = App.Model.Music;
					url = App.apiURL + '/search.php?search='+params.search+'&page='+App.resultsPage;
				}else{
					collection = App.Model.Videos;
					url = App.apiURL + '/search-videos.php?search='+params.search+'&page='+App.resultsPage;
				}
				
				$.ajax({
					url: url,
					beforeSend: function(xhr) {
						App.Regions.Content.$el.append('<span id="more-results"><center><img src="/loading-bars.svg"></center></span>');
					}
				}).done(function(data) {
					if(typeof data === 'string'){
						data = JSON.parse(data);
					}
					$('#more-results').remove();
					var prevCount = collection.length;
					_.each(data, function(value, index){
						collection.add(value);
					});
					
					var nextPage = _.template($('#results-tpl').html())({count:prevCount});
					App.Regions.Content.$el.append(nextPage);
					_this._loadingNext = false;
				});
			}
		},

		showAlbums: function(artist) {
			var me = this,
				searchParams = this.getParams();
			$.ajax({
				url: App.baseURL + '/api/albums.php?artist=' + App.Util.seoFriendlyDashes(artist),
				//url: App.apiURL + '/bio/' + App.Util.seoFriendlyDashes(searchParams.search),
				beforeSend: function(xhr) {
					//xhr.overrideMimeType("text/plain; charset=x-user-defined");
				}
			}).done(function(data) {
				if (data) {
					var albums = JSON.parse(data);
					if (albums.length > 0) {
						App.Util.waitTillReady('#albums', function() {
							$('#albums').html(_.template($('#albums-tpl').html())(albums));
							//me.initializeAlbumsContainer.apply(me, arguments);
							var initializeAlbumsContainer = _.bind(me.initializeAlbumsContainer, me);
							setTimeout(function(){ initializeAlbumsContainer(); }, 3000);
						});
					}
				}
			});
		},

		showBio: function() {
			var me = this,
				searchParams = this.getParams();
			$.ajax({
				url: App.baseURL + '/api/bio.php?artist=' + App.Util.seoFriendlyDashes(searchParams.search),
				//url: App.apiURL + '/bio/' + App.Util.seoFriendlyDashes(searchParams.search),
				beforeSend: function(xhr) {
					//xhr.overrideMimeType("text/plain; charset=x-user-defined");
				}
			}).done(function(data) {
				if (data) {
					var bio = JSON.parse(data);
					if (typeof bio[0] !== 'undefined') {
						App.Util.waitTillReady('#bio', function() {
							$('#bio').html(_.template($('#bio-tpl').html())(bio[0]));
							me.showAlbums(bio[0].artist);

							var bioImage = $('.bio-image img');
							$('#bio').css('visibility', 'hidden');
							bioImage.one('load', function() {
								//$('.bio-image img').css('visibility', 'visible');
								var showBioImage = function() {
									$('#bio').animo({
										animation: 'fadeInUp',
										duration: 0.4,
										keep: false
									}, function(){
/* 										bioImage.animo({
											animation: 'jelly',
											duration: 1,
											keep: false
										}); */
									}).css('visibility', 'visible');
								};
								showBioImage();
							}).each(function() {
								if (this.complete) {
									$(this).load();
								}
							});
						});
					}
				}
			});
		},
		fetchResults: function(search) {
			App.vent.trigger('loading:start', search);
			//App.vent.trigger('message', '', 'Searching Network...');

			//Update:true
			//that way models will only be add/remove/change'd rather than recreated on each fetch.
			var model = App.resultsType === 'music' ? App.Model.Music : App.Model.Videos;

			model.fetch({
				update: true
			}).done(function(data) {
				App.vent.trigger('search:results', App.resultsType);
				if (App.resultsType === 'music') {
					App.Search.options.showBio();
				}
			});
		},
		getParams: function() {
			return {
				search: $('#top_search_input').val(),
				page: App.resultsPage = App.resultsPage ? App.resultsPage : 1,
				type: App.resultsType = App.resultsType ? App.resultsType : 'music',
			};
		},
		fetchBitrate: function(songId, playtime, callback) {
			$.ajax({
				type: "GET",
				url: App.apiURL + "/bitrate.php?id=" + songId + "&seconds=" + playtime,
			}).done(function(data) {
				if (data) {
					data = JSON.parse(data);
				}
				callback.call(this, data);
			});
		},
		fetchAlbumInfo: function(songTitle, callback) {
			$.ajax({
				type: "GET",
				url: App.apiURL + "/albuminfo/" + App.Util.seoFriendlyDashes(songTitle) + "",
			}).done(function(data) {
				if (data) {
					data = JSON.parse(data);
				}
				callback.call(this, data);
			});
		}
	});
})(window.App);