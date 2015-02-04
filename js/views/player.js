(function(App) {
	'use strict';

	var Player = Backbone.Marionette.ItemView.extend({
		template: '#player-tpl',
		className: 'player',
		
		controller: function(){
			return new App.Controller.Player({view:this});
		},

		initialize: function() {
			/* set controller */
			this.controller = App.Controller.Player = this.controller();
			App.vent.on('toggle:playlist', _.bind(this.togglePlaylist, this));
		}
	});
	App.View.Player = Player;
})(window.App);