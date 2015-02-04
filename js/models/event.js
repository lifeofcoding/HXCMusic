(function(App) {
    'use strict';

    var event = Backbone.Model.extend({
        events: {},

        idAttribute: 'id',

        initialize: function() {}
    });

    App.Models.Event = App.Model.Event = event;
})(window.App);