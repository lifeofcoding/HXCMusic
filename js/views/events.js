(function(App) {
	'use strict';

	var events = Backbone.Marionette.ItemView.extend({
		template: '#events-tpl',
		
		ui: {
			zipCode: '#zip_code',
			country: '#country',
			miles: '#miles',
			eventsButton: '#find-events'
		},
		
		collectionEvents:{
			'sync': 'render'
		},

		events: {
			'click @ui.eventsButton': 'findEvents'
		},
		
		initialize: function() {
			//this._destroy = this.destroy;
			//this.destroy = this.destroyAnimation;
		},
		
		findEvents: function(e){
			e.preventDefault();
			var _this = this;
			if(this.ui.zipCode.val() === null || this.ui.zipCode.val() === ''){
				App.vent.trigger('message', 'Error', 'Please enter zipcode to search for events');
				return;
			}
			var options = {
				country: this.ui.country.val(),
				zipCode: this.ui.zipCode.val(),
				miles: this.ui.miles.val(),
				page: 1
			};
			var url = this.collection.getURL(options),
				buttonText = this.ui.eventsButton.html(),
				currentWidth = this.ui.eventsButton.width(),
				currentHeight = this.ui.eventsButton.height(),
				loadingBars = '<center>';
				loadingBars += '<img src="/loading-bars-white.svg" style="margin-top:-7px;height:32px;width:auto;">';
				loadingBars += '</center>';

			this.ui.eventsButton.css({
				'width':currentWidth+'px',
				'height':currentHeight+'px'
			}).html(loadingBars);

			this.collection.fetch({url:url, update:true}).done(function(){
				//_this.ui.eventsButton.html(buttonText);
			});
		},
		
		_destory: null,
		
		destroyAnimation: function(){
			debugger;
			var _this = this;
			App.viewport.currentView.Content.$el.animo({
				animation: "magictime spaceOutLeft",
				keep: false,
				duration: 1
			}, function() {
				_this._destroy();
				App.viewport.currentView.Content.$el.animo({
					animation: "magictime spaceInRight",
					keep: false,
					duration: 1
				}, function() {

				}).show();
			}).show();
		}

	});
	App.View.Events = events;
})(window.App);
