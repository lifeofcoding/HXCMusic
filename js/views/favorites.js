(function(App) {
	'use strict';

	var favorites = Backbone.Marionette.ItemView.extend({
		template: '#favorites-tpl',
		//className: 'player',
		
		controller: function(){
			return new App.Controller.Favorites({view:this});
		},
		
		collectionEvents: {
			'sync': 'render',
			'remove': 'render'
		},
		
		background: {
			url: App.baseURL + '/images/love.jpg',
			options: {fade: 1000, centeredX: false, centeredY: true},
			changeOnExit: false
		},

		initialize: function() {
			this.controller = this.controller();
		},
		
		onRender: function(){
			if(typeof this.background !== 'undefined' && this.background.url){
				if(App.Background[App.Util.getFilename(this.background.url)]){
					App.setBackground(this.background.url, this.background.options);
				}
			}
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
		},
		
		onDestroy: function(){
			//$('#middle').backstretch(App.baseURL + "/images/love.min.jpg", {fade: 1000, centeredX: false, centeredY: true});
		}

	});
	App.View.Favorites = favorites;
})(window.App);