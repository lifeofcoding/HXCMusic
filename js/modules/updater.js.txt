(function(App) {
	'use strict';
	App.module("Updater", {
		initialize: function(moduleName, app, options) {
			//Check for update every 5 minutes
			options.checkForUpdate();
		},
		checkForUpdate: function() {
			$.ajax({
				url: App.apiURL + '/updater.php'
			}).done(function(data) {
				data = JSON.parse(data);

				var hasUpdate = false;
				for(var i = 0, len = data.length; i < len; ++i){
					if(typeof App.Updater[data[i].filename] === 'undefined'){
						App.Updater[data[i].filename] = data[i].modifiedAt;
					}else{
						if(App.Updater[data[i].filename] !== data[i].modifiedAt){
							hasUpdate = true;
						}
						App.Updater[data[i].filename] = data[i].modifiedAt;
					}
				}

				if(hasUpdate){
					App.vent.trigger('update');
				}else{
					console.info('No Update Available');
					setTimeout(function(){
						App.Updater.options.checkForUpdate();
					}, 300 * 1000); //300 * 1000 or 300000 for 5min
				}
			});
		}
	});
})(window.App);