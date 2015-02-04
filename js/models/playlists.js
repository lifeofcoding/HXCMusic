(function(App) {
	'use strict';

	var playlists = Backbone.Collection.extend({
		urlRoot: App.apiURL + '/playlists',

		url: function(options) {
			return App.apiURL + '/playlists/userId/' + App.User.userId;
		},
		
		schema: {
			id: {type: 'number'},
			title: {type: 'string'},
			userId: {type: 'string'}
		},
		
		setupSchema: function(){
			var schema = new Backbone.Schema(this);
			
			var key;
			for(key in this.schema){
				schema.define(key, { type: this.schema[key].type });
			}
		},
		
		initialize: function(){
			//this.setupSchema();
			for(var i = 0, len = window.playlists.length; i < len; ++i){
				this.add(window.playlists[i]);
			}
		}
		
	});
	App.Models.Playlists = playlists;
	App.Model.Playlists = new playlists();
})(window.App);