(function(App) {
	'use strict';
	var index = Marionette.Controller.extend({

		initialize: function(options) {
			if(App.User.isLoggedIn){
				App.Router.navigate('/favorites', {
					trigger: true,
					replace: false
				});
				return;
			}
			if (App.Router.currentController !== null) {
				App.Router.currentController.destroy();
			}
			App.Router.displayHomepage();
			$.ajax({
				url: App.baseURL + "/api/suggest.php",
				//url: App.apiURL + "/suggestions",
				beforeSend: function(xhr) {
					//xhr.overrideMimeType("text/plain; charset=x-user-defined");
				}
			}).done(function(data) {
				App.Suggested = JSON.parse(data);
				App.vent.trigger('show:homepage');

				var showSearchBox = function() {
					setTimeout(function() {
						//$('#homepage-title h1').hide();
						$('#homepage-title h1').animo({
							animation: 'fadeInUp',
							duration: 0.4,
							keep: false
						}, function() {
							$('#homepage-searchbox').animo({
								animation: 'fadeInUp',
								duration: 0.4,
								keep: false
							}).css('visibility', 'visible');
						}).css('visibility', 'visible');
					}, 1000);
					//$('#homepage-content').hide();
					//$('#homepage-content').animo({ animation: 'vanishIn', duration: 0.4, keep: false }).show();
				};

				if (App.isReady) {
					showSearchBox();
				} else {
					App.vent.on('onReady', function() {
						showSearchBox();
					});
				}

				var options = {
					minMargin: 0,
					maxMargin: 0,
					itemSelector: '.homepage-tiles'
				};
				$('#homepage').rowGrid(options);
			});
		},

		doStuff: function() {
			this.trigger("stuff:done", this.stuff);
		}

	});
	App.Controller.Index = index;
})(window.App);