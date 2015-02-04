(function(App) {
	'use strict';

	var homepage = Backbone.Marionette.ItemView.extend({
		template: '#homepage-tpl',
		//className: 'player',

		ui: {},

		events: {},

		initialize: function() {
			this._destroy = this.destroy;
			this.destroy = this.destroyAnimation;
			App.vent.once('close:homepage',  _.bind(this.destroy, this));
		},
		
		onBeforeDestroy: function(){
			$('#explore').css('visibility', 'visible');
			$('#homepage').hide();
			$('#explore_nav').css('visibility', 'visible');
		},

		onShow: function() {

		},
		
		_destory: null,
		
		destroyAnimation: function(){
			var _this = this;
			$('#homepage').animo({ animation: 'vanishOut', duration: 0.4, keep: false }, function(){
				$('#explore').css('visibility', 'visible');
				
				$('#homepage').hide();
				$('#explore_nav').css('visibility', 'visible');
				_this._destroy();
			});
		}

	});
	App.View.Homepage = homepage;
})(window.App);