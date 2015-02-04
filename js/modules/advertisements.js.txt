(function(App) {
	'use strict';
	App.module("Ads", {
		initialize: function(moduleName, app, options) {
			//Add app shortcuts
			//I.E. App.websiteTitle, App.webRoot, ...
			var key;
			for (key in options) {
				if (key !== 'initialize') {
					this[key] = options[key];
				}
			}
		},
		viewport: function(){ return App.viewport.currentView.ui; },
		bottom: function(){
			this.viewport().bottomAdWrapper.html('<div id="bottom_ad">'+this.options.displayAd('bottom')+'</div>');
		},
		displayAd: function(position){
			$.getScript('//cdn.chitika.net/getads.js');
			if (window.CHITIKA === undefined) { window.CHITIKA = { 'units' : [] }; }
			var unit = {"calltype":"async[2]","publisher":"ccastillo","width":550,"height":250,"sid":"Index HXC","color_site_link":"ffffff","color_button":"1b79b8","color_button_text":"ffffff"};
			var placement_id = window.CHITIKA.units.length;
			window.CHITIKA.units.push(unit);
			return '<div id="chitikaAdBlock-' + placement_id + '"></div>';
		}
	});
})(window.App);