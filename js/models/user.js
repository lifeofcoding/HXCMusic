(function(App) {
	'use strict';

	var user = Backbone.Model.extend({
		urlRoot: App.apiURL + '/users',

		url: function(options) {
			return App.apiURL + '/users/' + App.User.id;
		},
		
		schema: {
			id: {type: 'number'},
			downloadLimit: {type: 'number'},
			userLevel: {type: 'number'},
			isLoggedIn: {type: 'boolean'},
			dailyDownloads: {type:'number'},
			totalDownloads: {type:'number'}
		},
		
		defaults: window.preloadData,

		idAttribute: 'id',

		//originalFetch: Backbone.fetch,

		//test: Backbone.fetch,
		orignalFetch: Backbone.Collection.prototype.fetch,

		fetchTesting: function(options) {
			var me = this;
			if (options.local) {
				this.localStorage = App.localStore;
				this.orignalFetch();
				//return Backbone.localSync.apply(this, ['read', App.Model.User, {url:this.url}]);
			} else {
				this.localStorage = null;
				this.orignalFetch({
					success: function() {
						me.localStorage = App.localStore;
					}
				});
				//return Backbone.ajaxSync.apply(this, ['read', App.Model.User, {url:this.url}]);
			}
		},
		
		setupSchema: function(){
			var schema = new Backbone.Schema(this);
			
			var key;
			for(key in this.schema){
				schema.define(key, { type: this.schema[key].type });
			}
		},

		initialize: function() {
			delete window.preloadData;
			this.setupSchema();
			
			var me = this;
			this.bind('change', this.setShortcuts);

			App.User = this.defaults;
			
			App.vent.on('onReady', function(){
				if(App.User.isLoggedIn){
					App.vent.trigger('ui:loggedin');
				}else{
					var location = document.location.href;
					if(location.indexOf('favorites') > -1 || location.indexOf('upgrade') > -1){
						setTimeout(function(){
							App.Router.navigate('/', {
								trigger: true,
								replace: true
							});
						}, 500);
					}
				}
			});
/* 			var HXCID = $.cookie('HXCID');
			if (typeof HXCID !== 'undefined') {
				App.User.id = HXCID;

				this.fetch().done(function() {
					
						App.vent.trigger('ui:loggedin');
					
				});
			} else {
				this.set({
					isLoggedIn: false
				});
				var checkPage = function() {
					if (typeof App.currentPage !== 'undefined') {
						if (App.currentPage === 'showFavorites' || App.currentPage === 'showUpgrade') {
							App.Router.navigate('/', {
								trigger: true,
								replace: true
							});
						}
					} else {
						setTimeout(function() {
							checkPage();
						}, 500);
					}
				};
				checkPage();
			} */
		},

		/* Overwrite toJson method so when updating model on server
		/* instead of sending all attributes look through the changed object
		/* and only send what has changed
		*/
		toJSON: function() {
			var key, results = {}, hasKey;
			for (key in this.attributes) {
				hasKey = _.has(this.changed, key);
				if (hasKey) {
					results[key] = this.get(key);
				}
			}
			/* Since we clone the favorites collection to Users model we need to remove it before any submittions */
			delete results.favorites;
			return results;
		},

		setShortcuts: function() {
			var key, model;
			if (typeof App.Model.User.attributes[0] === 'object') {
				App.Model.User.attributes = App.Model.User.attributes[0];
			}

			model = App.Model.User.attributes;
			for (key in model) {
				if (key === 'dailyDownloads' || key === 'userLevel' || key === 'totalDownloads' || key === 'id') {
					model[key] = typeof model[key] === 'string' ? Number(model[key]) : model[key];
				}
				if (key === 'password') {
					App.Model.User.unset('password', {
						silent: true
					});
				}
				if (key === 'lastDownloaded' && App.User.userLevel < 5) {
					if (Number(model[key]) > moment(moment().add('hours', 24)._d).unix()) {
						model.dailyDownloads = 0;
					}
				}
			}

			if (model.userLevel > 0) {
				model.isLoggedIn = true;
			}

			App.User = model;
		},
	});
	App.Models.User = user;
	//Auto fetch on init
	App.Model.User = new user();
})(window.App);