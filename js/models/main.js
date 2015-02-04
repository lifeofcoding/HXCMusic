(function(App) {
    'use strict';

    var Main = Backbone.Model.extend({
        events: {
            'change:main': 'build',
        },

        idAttribute: 'main',

        initialize: function() {
            this.build();
        },

        build: function() {
			//this.set({error: 'yay!'});
        }
    });

    App.Model.Main = Main;
})(window.App);