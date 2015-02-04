(function(App) {
	'use strict';

	var videos = Backbone.Collection.extend({
		url: function(){
			var params = App.Search.options.getParams();
			return App.baseURL + '/api/search-videos.php?search='+params.search+'&page='+params.page;
		}
	});
	
	App.Models.Videos = videos;
	App.Model.Videos = new videos();
})(window.App);