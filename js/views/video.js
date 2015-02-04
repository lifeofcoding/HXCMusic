(function(App) {
	'use strict';

	var video = Backbone.Marionette.ItemView.extend({
		template: '#video-tpl',
		//className: 'player',
		ui: {},

		events: {},

		initialize: function() {},

		onShow: function() {},

		onClose: function() {}
	});
	App.View.Video = video;
})(window.App);