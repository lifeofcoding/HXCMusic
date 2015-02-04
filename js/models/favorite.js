(function(App) {
    'use strict';

    var favorite = Backbone.Model.extend({
		url: App.apiURL + '/favorites',
		
        events: {},

        initialize: function() {}
    });

    App.Models.Favorite = App.Model.Favorite = favorite;
})(window.App);