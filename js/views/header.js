(function(App) {
	'use strict';

	var header = Backbone.Marionette.ItemView.extend({
		template: '#header-tpl',

		events: {},

		initialize: function() {}
	});

	App.View.Header = header;
})(window.App);
