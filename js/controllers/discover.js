(function(App) {
	'use strict';
	var discover = Marionette.Controller.extend({

		initialize: function(options) {
			if (App.Router.currentController !== null) {
				App.Router.currentController.destroy();
			}
			App.Router.currentController = this;
		}

	});
	App.Controller.Discover = discover;
})(window.App);