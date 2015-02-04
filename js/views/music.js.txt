(function(App) {
	'use strict';

	var music = Backbone.Marionette.ItemView.extend({
		template: '#music-tpl',

		className: '',

		ui: {
			loadingImg: '#loading-genres img'
		},

		events: {
			'change .genre_dropdown': 'showGenre'
		},
		
		background: {
			url: App.baseURL + '/images/grey_bg5.jpg',
			options: {fade: 1000, centeredX: false, centeredY: true},
			changeOnExit: App.baseURL + '/images/love.jpg'
		},

		initialize: function() {
			var _this = this;
			App.Util.afterPageRender(function(){
				$(App.Regions.contentWindow.el).on('scroll', function(){
					if(App.currentPage === 'music'){
						var elementCount = Math.round($('.song_row').length / 3); //start fetching after scrolling 1/3 of the results
						var isInView = App.Util.isInViewport($('.song_row').get($('.song_row').length - elementCount), $(App.Regions.contentWindow.el));
						if(isInView && App.Page.Music.length > 10){
							_this.nextPage();
						}
					}
				});
			});
		},

		showGenre: function() {
			var me = this,
				genre = $('.genre_dropdown').val();
			$.ajax({
				url: App.apiURL + '/' + genre + '/music/' + App.resultsPage,
				beforeSend: function(xhr) {
					me.ui.loadingImg.show();
				}
			}).done(function(data) {
				App.Page.Music = JSON.parse(data);
				App.vent.trigger('setPageSubTitle', App.Util.capitalizeWords(genre) + ' Artists');
				App.vent.trigger('hidePlayAll');
				me.render();
				$('.genre_dropdown').val(genre);
			});
		},
		
		_loadingNext: false,
		
		_maxPages: 5,
		
		nextPage: function(){
			var _this = this,
				genre = $('.genre_dropdown').val(),
				model;
			if(!this._loadingNext && this._maxPages > App.resultsPage){
				App.resultsPage = (App.resultsPage + 1);
				_this._loadingNext = true;
				var url = function(){
					if(genre){
						return App.apiURL + '/' + genre + '/music/' + App.resultsPage;
					}else{
						return App.apiURL + '/music/' + App.resultsPage;
					}
				};
				$.ajax({
					url: url(),
					beforeSend: function(xhr) {
						//xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
						App.Regions.Content.$el.append('<span id="more-results"><center><img src="/loading-bars.svg"></center></span>');
					}
				}).done(function(data) {
					$('#more-results').remove();
					data = JSON.parse(data);
					var prevCount = App.Page.Music.length;
					
					$.each(data, function(index, record){
						App.Page.Music.push(record);
					});
					
					var nextPage = _.template($('#music-tpl').html())({count:prevCount});
					App.Regions.Content.$el.append(nextPage);
					_this._loadingNext = false;
				});
			}
		},
		
		templateHelpers: {
			count: 0
		},
		
		onBeforeDestroy: function(){
			App.resultsPage = 1;
		},
		
		onRender: function(){
			if(typeof this.background !== 'undefined' && this.background.url){
				if(App.Background[App.Util.getFilename(this.background.url)]){
					App.setBackground(this.background.url, this.background.options);
				}
			}
		},
		
		onShow: function(){
			//$('#middle').backstretch(App.baseURL + "/images/grey_bg5.jpg", {fade: 1000, centeredX: false, centeredY: true});
		},

		onDestroy: function() {
			if(typeof this.background !== 'undefined' && this.background.changeOnExit){
				App.setBackground(this.background.changeOnExit, this.background.options);
			}
		}
	});
	App.View.Music = music;
})(window.App);