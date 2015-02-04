(function(App) {
	'use strict';

	var upgrade = Backbone.Marionette.ItemView.extend({
		template: '#upgrade-tpl',
		
		ui: {},

		events: {},
		
		background: {
			url: App.baseURL + '/images/planet%20on%20the%20valley.jpg',
			options: {fade: 1000, centeredX: false, centeredY: true},
			changeOnExit: App.baseURL + '/images/love.jpg'
		},
		
		initialize: function(){

		},
		
		onRender: function(){
			if(typeof this.background !== 'undefined' && this.background.url){
				if(App.Background[App.Util.getFilename(this.background.url)]){
					App.setBackground(this.background.url, this.background.options);
				}
			}
		},
		
		onShow: function() {
			var checkDefined = function(){
				if(typeof App.User !== 'undefined' && typeof App.User.username !== 'undefined'){
					$('input[name="os0"]').val(App.User.username);
				}else{
					setTimeout(function(){ checkDefined(); }, 1000);
				}
			};
			checkDefined();
		},

		onDestroy: function() {
			if(typeof this.background !== 'undefined' && this.background.changeOnExit){
				App.setBackground(this.background.changeOnExit, this.background.options);
			}
		}

	});
	App.View.Upgrade = upgrade;
})(window.App);