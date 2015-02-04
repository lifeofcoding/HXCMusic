(function(App) {
	'use strict';
	var upgrade = Marionette.Controller.extend({

		initialize: function(options) {
			if (App.Router.currentController !== null) {
				App.Router.currentController.destroy();
			}
			$(document).ready(function() {
				App.vent.trigger('loading:end');
				App.vent.trigger('hidePlayAll');
				App.vent.trigger('setPageTitle', 'Premium Access');
				App.vent.trigger('setPageHeader', 'Premium Access');
				App.vent.trigger('setPageSubTitle', 'Unlimited Daily Downloads, For Life.');
				App.vent.trigger('show:content', new App.View.Upgrade());
			});
		}

	});
	App.Controller.Upgrade = upgrade;
})(window.App);