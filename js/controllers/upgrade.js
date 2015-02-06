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
				App.Router.setPageDetails({
					pageTitle:'Premium Access',
					pageHeader:'Premium Access',
					subTitle:'Unlimited Daily Downloads, For Life.'
				});
				
				App.vent.trigger('show:content', new App.View.Upgrade());
			});
		}

	});
	App.Controller.Upgrade = upgrade;
})(window.App);
