(function(App) {
    'use strict';

    var mp3 = Backbone.Model.extend({
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

	App.Models.MP3 = mp3;
    App.Model.MP3 = new mp3();
})(window.App);