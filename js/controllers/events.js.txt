(function(App) {
	'use strict';
	var events = Marionette.Controller.extend({

		initialize: function(options) {
			if (App.Router.currentController !== null) {
				App.Router.currentController.destroy();
			}
			App.vent.trigger('loading:end');
			App.vent.trigger('setPageTitle', 'Events');
			App.vent.trigger('setPageHeader', 'Events');
			App.vent.trigger('setPageSubTitle', 'Artist playing near you');
			App.vent.trigger('hidePlayAll');
			App.vent.trigger('show:content', new App.View.Events({
				collection: App.Model.Events
			}));
		}

	});
	App.Controller.Events = events;
})(window.App);