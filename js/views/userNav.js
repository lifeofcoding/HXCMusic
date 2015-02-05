(function(App) {
	'use strict';

	var userNav = Backbone.Marionette.ItemView.extend({
		template: '#userNav-tpl',
		//className: 'player',

		ui: {
			uploadImageLink: '.upload-image'
		},

		events: {
			'click @ui.uploadImageLink': 'uploadImage'
		},

		onShow: function() {
			var _this = this;
			_this.$el.css('visibility', 'hidden');
			
			_this.$el.find('img').one('error', function() {
				_this.$el.css('visibility', 'visible');
				var showUserNav = function() {
					_this.$el.find('img').attr('src', App.baseURL + '/images/noimage.jpg');
					_this.$el.animo({
						animation: 'vanishIn',
						duration: 0.5,
						keep: false
					});
				};
				showUserNav();
			});
			_this.$el.find('img').one('load', function() {
				_this.$el.css('visibility', 'visible');
				var showUserNav = function() {
					_this.$el.animo({
						animation: 'vanishIn',
						duration: 0.5,
						keep: false
					});
				};
				showUserNav();
			}).each(function() {
				if (this.complete) {
					$(this).load();
				}
			});
		},

		onClose: function() {

		},
		
		uploadImage: function(e) {
			if (e) {
				e.preventDefault();
			}
			if (typeof App.Modal.Uploader !== 'undefined') {
				App.Modal.Uploader.show();
				App.Modal.Uploader.messi.addClass('messi-open');
				return;
			}
			var me = this;
			App.Modal.Uploader = new Messi(_.template($('#uploader-tpl').html()), {
				modal: true,
				show: false,
				title: 'Upload Image',
				buttons: [{
					id: 0,
					label: 'Cancel',
					val: 'cancel',
					class: 'cancel-button'
				}, {
					id: 1,
					label: 'Upload',
					val: 'upload',
					class: 'upload-button'
				}]
			});

			App.Dialog.showModal(App.Modal.Uploader);

			App.Modal.Uploader.messi.find('.btnbox').unbind('click');
			$(document).on('click', '.upload-button', function(e) {
				var formData = new FormData($('.uploader-wrapper')[0]);
				$.ajax({
					url: App.baseURL + '/api/uploader.php',
					type: 'POST',
					xhr: function() {
						var myXhr = $.ajaxSettings.xhr();
						if (myXhr.upload) { // Check if upload property exists
							//myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
						}
						return myXhr;
					},
					// Form data
					data: formData,
					//Options to tell jQuery not to process data or worry about content-type.
					cache: false,
					contentType: false,
					processData: false
				}).done(function(data) {
					data = JSON.parse(data);
					App.Modal.Uploader.hide();
					if (typeof data.success !== 'undefined') {
						App.Model.User.save({
							image: data.url
						});

						me.updateImage();
						App.vent.trigger('message', 'HXC Message Center', 'Image uploaded successfully!');
					} else {
						//login error
						App.vent.trigger('message', 'Error', 'Image upload error');
					}
				});
			});
			$(document).on('click', '.cancel-button', function(e) {
				App.Dialog.hideModal(App.Modal.Uploader);
			});
		}

	});
	App.View.UserNav = userNav;
})(window.App);
