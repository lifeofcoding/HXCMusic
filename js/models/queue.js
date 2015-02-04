(function(App) {
	'use strict';

	var queue = Backbone.Collection.extend({
		localStorage: new Backbone.LocalStorage('HXCQueue'),
		//model: App.Model.MP3,
		initialize: function(){
			var _this = this;
			this.fetch().done(function(){
				_this.on('add', function(model){
					model.save();
				});
				_this.on('remove', function(model){
					_this.sync('delete', model);
				});
			});
		}
	});
	
	App.Models.Queue = queue;
	App.Model.Queue = new queue();
})(window.App);