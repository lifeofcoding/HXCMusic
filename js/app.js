var App = new Backbone.Marionette.Application();
_.extend(App, {
	Regions: {},
    Controller: {},
    View: {},
    Model: {},
    Page: {},
	Player: {},
	Modal: {},
	Models: {},
	isReady: false,
	baseURL: 'http://' + document.location.host,
	apiURL: 'http://api.' + document.location.host.replace('development.',''),
	socket: null,
	usersOnline: 0,
	songsStreaming: [],
	currentView: null,
	isGoogle: false,
	favicon: new Favico({
    	animation:'popFade'
	})
});

App.addRegions({
	viewport: '#wrapper'
});

App.addInitializer(function (options) {
	var mainWindow = new App.View.Viewport();
	try {
		App.viewport.show(mainWindow);
	} catch(e) {
		console.error('Couldn\'t start app: ', e, e.stack);
	}
	
	Marionette.Region.prototype.attachHtml = function(view){
		if(this.$el.attr('id') !== 'homepage'){
			this.$el.hide();
			this.$el.html(view.el);
			//this.$el.animo({ animation: 'fadeInUp', duration: 0.5, keep: false }).show();
			//this.$el.show();
			var content = this.$el;
			//content.animo({animation: "magictime fadeOut", keep: false, duration: 0.4}, function() {
				content.animo({animation: "magictime fadeInUp", keep: false, duration: 0.5}, function() {

				}).show();
			//}).show();
		}else{
			this.$el.hide();
			this.$el.html(view.el).show();
		}
	};
});