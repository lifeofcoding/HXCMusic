(function(App) {
	'use strict';

	var music = Backbone.Collection.extend({
		url: function(){
			var params = App.Search.options.getParams();
			return App.apiURL + '/search.php?search='+params.search+'&page='+params.page;
		},
		initialize: function(){
			this.on('add', function(model){
				
			});
		}
	});
	
	App.Models.Music = music;
	App.Model.Music = new music();
})(window.App);