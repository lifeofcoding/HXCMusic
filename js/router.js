(function(App) {
	'use strict';
	var router = Backbone.Marionette.AppRouter.extend({
		controller: new App.Controller.Routes(),
		
		currentController: null,
		
		initialize: function(options) {
			var me = this;
			this.initializeLinks();
			this.on('route', function(route, params) {
				if (route !== 'index') {
					App.Util.afterPageRender(function() {
						me.hideHomepage();
					});
					/*
					if(typeof App.viewport.currentView !== 'undefined'){
						var currentPage = App.currentPage;
						App.viewport.currentView.Content.$el.animo({animation: "magictime fadeOut", keep: false, duration: 0.5}, function() {
							if(currentPage === App.currentPage){
								App.viewport.currentView.Content.$el.hide();
							}
						});
					}
					*/
				}
				App.currentPage = route;
				App.Util.waitTillReady('#explore_nav', function() {
					App.vent.trigger('toggle:menu', route);
					$('#right').animate({
						scrollTop: 0
					}).promise().then(function() {
						// callback code here
					});
				});
			});
		},
		appRoutes: {
			'': 'index',
			'music': 'music',
			'music/:search': 'musicResults',
			'videos/:search': 'videoResults',
			'search/:search/:page/mp3': 'musicResults',
			'search/:search/:page/video': 'videoResults',
			'video/:videoId/:title': 'video',
			'favorites': 'favorites',
			'upgrade': 'upgrade',
			'events': 'events',
			'forums': 'forums',
			'account': 'account',
			'profile': 'account',
			'videos': 'videos',
			'discover': 'discover',
			'*path': 'index' //needs to be last so this does not steal route of others
		},

		displayHomepage: function() {
			var exec = function(){
				$('#explore').css('opacity', 'hidden');
				$('#explore_nav').css('visibility', 'hidden');
				$('#homepage').show();
			};
			if (App.isReady) {
				exec();
			} else {
				App.vent.on('onReady', function() {
					exec();
				});
			}
		},

		hideHomepage: function() {
			var exec = function(){
				App.vent.trigger('close:homepage');
			};
			if (App.isReady) {
				exec();
			} else {
				App.vent.on('onReady', function() {
					exec();
				});
			}
			/* 			$('#homepage').animo({ animation: 'vanishOut', duration: 1, keep: false }, function(){
				$('#explore').css('visibility', 'visible');
				$('#homepage').hide();
				$('#explore_nav').css('visibility', 'visible');
			}); */
		},

		startSearch: function(search) {
			$('#top_search_input').val(App.Util.removeDashes(search));
			App.vent.trigger('search', search);
		},

		setPageHeader: function(title) {
			App.vent.trigger('setPageHeader', title);
		},

		setPageSubTitle: function(title) {
			App.vent.trigger('setPageSubTitle', title);
		},

		setPageTitle: function(title) {
			$('title').html(App.Util.capitalizeWords(title) + ' at ' + App.websiteTitle + '&trade;');
		},

		initializeLinks: function() {
			$(document).on("click", "a:not([data-bypass])", function(evt) {
				var href = {
					prop: $(this).prop("href"),
					attr: $(this).attr("href")
				};
				var root = location.protocol + "//" + location.host + Backbone.history.options.root;

				if (href.prop && href.prop.slice(0, root.length) === root) {
					evt.preventDefault();
					var page = href.attr;

					if (App.Util.contains(page, App.baseURL)) {
						page = page.replace(App.baseURL, '');
					}
					Backbone.history.navigate(page, true);
				}
			});
		},
		
		onRoute: function(){

		}
	});

	App.Router = new router();

	/* Previous method: root: '/' + document.location.href.split('/')[3] */
	Backbone.history.start({
		pushState: true,
		root: App.webRoot
	});

})(window.App);