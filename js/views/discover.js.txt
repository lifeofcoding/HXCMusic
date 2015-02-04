(function(App) {
	'use strict';

	var discover = Backbone.Marionette.ItemView.extend({
		template: '#discover-tpl',
		//className: 'player',
		
		ui: {},

		events: {},
		
		controller: function(){
			return new App.Controller.Discover({view:this});
		},
		
		background: {
			url: App.baseURL + '/images/graffiti.jpg',
			options: {fade: 1000, centeredX: false, centeredY: true},
			changeOnExit: App.baseURL + '/images/love.jpg'
		},
		
		initialize: function(){
			this.controller = this.controller();
		},
		
		onRender: function(){
			if(typeof this.background !== 'undefined' && this.background.url){
				if(App.Background[App.Util.getFilename(this.background.url)]){
					App.setBackground(this.background.url, this.background.options);
				}
			}
		},
		
		onShow: function() {
			//$('#middle').backstretch(App.baseURL + "/images/planet%20on%20the%20valley.jpg", {fade: 1000, centeredX: false, centeredY: true});
		},
		
		onDestroy: function() {
			if(typeof this.background !== 'undefined' && this.background.changeOnExit){
				App.setBackground(this.background.changeOnExit, this.background.options);
			}
		}

	});
	App.View.Discover = discover;
})(window.App);