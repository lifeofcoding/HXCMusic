/* Code from http://hayageek.com/login-with-google-plus-javascript-api/ */
(function(App) {
	'use strict';
	App.module("googleAPI", {
		initialize: function(moduleName, app, options) {
			var key;
			for (key in options) {
				if(key !== 'initialize'){
					this[key] = options[key];
				}
			}
			
			window.loginCallback = this.options.loginCallback;
		},
		logout: function() {
			gapi.auth.signOut();
			location.reload();
		},
		login: function() {
			var myParams = {
				'clientid': '867875238550-ko6v5l9e89hlg00l14l4potp3qqb81g6.apps.googleusercontent.com',
				'cookiepolicy': 'single_host_origin',
				'callback': 'loginCallback',
				'approvalprompt': 'force',
				'scope': 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/plus.profile.emails.read'
			};
			gapi.auth.signIn(myParams);
		},
		loginCallback: function(result) {
			var imageURL, message;
			gapi.client.load('plus', 'v1',function(){
				if (result['status']['signed_in']) {
					var request = gapi.client.plus.people.get({
						'userId': 'me'
					});
					request.execute(function(resp) {
						var email = '';
						if (resp.emails) {
							for (var i = 0; i < resp.emails.length; i++) {
								if (resp.emails[i].type === 'account') {
									email = resp.emails[i].value;
								}
							}
						}

						imageURL = App.Util.stripAfter(resp.image.url, '?sz='); //remove size param and add our own
						$.ajax({
							type: "POST",
							url: App.apiURL + "/imageFromURL.php",
							data: {
								url: imageURL + '?sz=190',
								username: App.Util.stripAfter(email, '@')
							}
						}).done(function(image){
							$.ajax({
								type: "POST",
								url: App.apiURL + "/signup.php",
								data: {
									username: App.Util.stripAfter(email, '@'), //get everything before @ from email
									image: image,
									email: email,
									isGoogle: true
								}
							}).done(function(data) {
								data = JSON.parse(data);
								if(typeof data.error !== 'undefined'){
									App.vent.trigger('message', 'Error', 'Username, Email or IP in use!<br>You may need to sign-up manually.');
									$('.messi:visible').animo({animation: "shake", keep: false, duration: 0.8});
								}else{
									if(typeof App.Modal.Signup !== 'undefined' && App.Modal.Signup.visible){
										App.Dialog.hideModal(App.Modal.Signup);
										message = 'Signed Up Successfully!';
									}
									if(typeof App.Modal.Login !== 'undefined' && App.Modal.Login.visible){
										App.Dialog.hideModal(App.Modal.Login);
										message = 'Welcome Back!';
									}
									_gaq.push(['_trackEvent', "User Events", "Registrations", data.email]);
									App.Model.User.set({
										id: Number(data.id),
										dailyDownloads: Number(data.dailyDownloads),
										downloadLimit: Number(data.userLevel) === 5 ? null : 3,
										email: data.email,
										ip: data.ip,
										isLoggedIn: true,
										totalDownloads: Number(data.totalDownloads),
										userId: data.userId,
										userLevel: Number(data.userLevel),
										username: data.username,
										image: data.image
									});

									App.vent.trigger('ui:loggedin');
									App.Router.navigate('upgrade', {
										trigger: true
									});
									App.vent.trigger('message', '', message);
								}
							});
						});
					});
				}
			});
		},
		onLoadCallback: function(){
			gapi.client.setApiKey('AIzaSyBbb6asME6pzoBZ7t9ZSfrUlZaoKejF610');
			gapi.client.load('plus', 'v1',function(){});
		}
	});
})(window.App);