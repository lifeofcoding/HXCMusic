(function(App) {
	'use strict';

	var albums = Backbone.Marionette.ItemView.extend({
		template: '#albums-tpl',

		ui: {},

		events: {},
		
		initialize: function() {},

		onShow: function() {},

		onClose: function() {}

	});
	App.View.Albums = albums;
})(window.App);