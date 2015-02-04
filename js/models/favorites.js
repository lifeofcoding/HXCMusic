(function(App) {
	'use strict';

	var favorites = Backbone.Collection.extend({
		url: function(params) {
			return App.apiURL + '/favorites/userId/' + App.User.userId;
		},
		initialize: function(){
			var _this = this;
			/* If user is already loggedin grab favorites from reloaded data */
			if(typeof window.favorites !== 'undefined'){
				var model;
				for(var i = 0, len = window.favorites.length; i < len; ++i){
					model = new App.Model.Favorite();
					for(var key in window.favorites[i]){
						model.set(key, window.favorites[i][key]);
					}
					_this.add(model);
				}
			}else{
				this.fetch();
			}
			
			/* Add this favorites collection nested in App.Model.User */
			App.Model.User.set('favorites', _.clone(this));
		}
	});
	App.Models.Favorites = App.Model.Favorites = favorites;
})(window.App);