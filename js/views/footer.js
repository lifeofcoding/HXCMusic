(function(App) {
	'use strict';

	var footer = Backbone.Marionette.ItemView.extend({
		template: '#footer-tpl',

		events: {},

		initialize: function() {}
	});

	App.View.Footer = footer;
})(window.App);
