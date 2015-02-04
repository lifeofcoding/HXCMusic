(function(App) {
	'use strict';

	var events = Backbone.Collection.extend({
		getURL: function(options){
			return App.apiURL + '/events/'+ options.zipCode +'/' + options.miles + '/' + options.page + '/' + App.Util.seoFriendlyDashes(options.country);
		}
	});
	App.Models.Events = events;
	App.Model.Events = new events();
})(window.App);