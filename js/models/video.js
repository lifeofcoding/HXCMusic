(function(App) {
    'use strict';

    var video = Backbone.Model.extend({
        events: {
            'change:all': 'playNext',
        },

        idAttribute: 'id',

        initialize: function() {
            //this.build();
        },

        playNext: function() {
			//this.set({error: 'yay!'});
        }
    });

	App.Models.Video = video;
    App.Model.Video = new video();
})(window.App);