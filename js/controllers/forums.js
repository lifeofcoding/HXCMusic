(function(App) {
	'use strict';
	var forums = Marionette.Controller.extend({

		initialize: function(options) {
			if (App.Router.currentController !== null) {
				App.Router.currentController.destroy();
			}
			//Backbone.history.history.back();
			//App.Router.navigate(-1, {trigger: false, replace: true});
/* 			$('#explore').css('opacity', 'hidden');
			$('#explore_nav').css('visibility', 'hidden');
			$('#homepage').show(); */

			var mAlert = Messi.alert('<span id="msg">We are currently rebuilding the forms, check back later.</span>');
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
		},

		doStuff: function() {
			this.trigger("stuff:done", this.stuff);
		}

	});
	App.Controller.Forums = forums;
})(window.App);