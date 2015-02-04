(function(App) {
	'use strict';
	$(document).on('ready', function() {
		App.start();
		App.isReady = true;
		App.vent.trigger('onReady');

		App.Background = {};
		App.Background['love.jpg'] = false;
		App.Background['planet%20on%20the%20valley.jpg'] = false;
		App.Background['grey_bg5.jpg'] = false;
		App.Background['graffiti.jpg'] = false;
		
		var img = new Image(), imgTwo = new Image(), imgThree = new Image(), imgFour = new Image();
		img.onload = function() {
			var backgroundParam = App.Util.getURLParameter('background'),
				background = backgroundParam ? App.baseURL + '/images/' + backgroundParam : App.baseURL + '/imagecache/image.php?src=/images/love.jpg&w='+$('#middle').width()+'&h='+$('#middle').height()+'&zc=1';
			$('#middle').backstretch(background, {
				fade: 1000,
				centeredX: false,
				centeredY: true
			});
			App.Background['love.jpg'] = true;
		};
		imgTwo.onload = function() {
			App.Background['planet%20on%20the%20valley.jpg'] = true;
		};
		imgThree.onload = function() {
			App.Background['grey_bg5.jpg'] = true;
		};
		imgFour.onload = function() {
			App.Background['graffiti.jpg'] = true;
		};
		img.src = App.baseURL + '/imagecache/image.php?src=/images/love.jpg&w='+$('#middle').width()+'&h='+$('#middle').height()+'&zc=1';
		imgTwo.src = App.baseURL + '/imagecache/image.php?src=/images/planet%20on%20the%20valley.jpg&w='+$('#middle').width()+'&h='+$('#middle').height()+'&zc=1';
		imgThree.src = App.baseURL + '/imagecache/image.php?src=/images/grey_bg5.jpg&w='+$('#middle').width()+'&h='+$('#middle').height()+'&zc=1';
		imgFour.src = App.baseURL + '/imagecache/image.php?src=/images/graffiti.jpg&w='+$('#middle').width()+'&h='+$('#middle').height()+'&zc=1';
	});
})(window.App);