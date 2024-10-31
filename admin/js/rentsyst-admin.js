(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(window).load(function() {
		var windowAuth = null;
		const pluginPrefix = 'rentsyst';

		function eventListeners() {
			$('#connect-rentsyst').on('click', function () {
				var url = $(this).attr('data-url');
				var width = 450,
					height = 730,
					left = (screen.width / 2) - (width / 2),
					top = (screen.height / 2) - (height / 2);
				windowAuth = window.open(url, 'Rentsyst', 'menubar=no,location=no,resizable=no,scrollbars=no,status=no, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left);
			});

			$('#disconnect-rentsyst').on('click', function () {
				sendAjax($(this).attr('data-url'), {}, function () {
					$('#status-no-connect').css('display', 'block');
					$('#status-connect').css('display', 'none');
					hiddenBoxSetting();
					hiddenVehicleSyncBlock();
				});
			});

			$('#upload-vehicle').on('click', function () {
				uploadVehicle($(this).attr('data-url'), function(date) {
					$('.vehicle-sync-time').html(date);
				});
			});

			$('#synchronize-settings-rentsyst').on('click', function () {
				uploadVehicle($(this).attr('data-url'), function(date) {
					$('.setting-sync-time').html(date);
				});
			});

			if($('#upload-vehicle').attr('data-auto-sync')) {
				uploadVehicle($('#upload-vehicle').attr('data-url'), function(date) {
					$('.vehicle-sync-time').html(date);
				});
			}

			$('*[data-show-subsettings]').on('click', function () {
				var subSettingsId = $(this).attr('data-show-subsettings');
				if($(this).prop('checked')) {
					$('#' + subSettingsId).removeClass('close');
					$('#' + subSettingsId).addClass('open');
				} else {
					$('#' + subSettingsId).removeClass('open');
					$('#' + subSettingsId).addClass('close');
				}
			})

			$("form").on('submit', function() {
				$('input[type="checkbox"]').each(function(){
					var isset = $(this).prop('checked') ? '1' : '0';
					$('<input type="hidden" name="' + $(this).attr('name') + '" value="0" />').insertBefore(this);
				});
			});

			$('input[name="enable_booking_button"]').on('click', function () {
				if($(this).prop('checked')) {
					$('#rentsyst_booking_button').css('display', 'block');
				} else {
					$('#rentsyst_booking_button').css('display', 'none');
				}
			});

			$('select[name="button_position"]').on('change', function () {
				$('#rentsyst_booking_button').removeClass('bottom right top left');
				$('#rentsyst_booking_button').addClass($(this).val())

			});$('input[name="button_text"]').on('keyup', function () {
				$('#rentsyst_booking_button .RentsystButtonText').html($(this).val());

			});

			$('input[name="button_color"]').on('change', function () {
				var color = $(this).val();
				$('#rentsyst_booking_button .RentsystButtonWave').css('color', color);
				$('#rentsyst_booking_button .RentsystButtonWave').css('border-color', color);
				$('#rentsyst_booking_button .RentsystButtonBackground').css('background-color', color);
			});

			$('input[name="button_animation"]').on('click', function () {
				if($(this).prop('checked')) {
					$('#rentsyst_booking_button .RentsystButtonWave').css('display', 'block');
				} else {
					$('#rentsyst_booking_button .RentsystButtonWave').css('display', 'none');
				}
			});


		}
		if($('.rentsyst-page-wrapper').length) {
			eventListeners();
		}

		window.addEventListener('message', function(e) {
			if(e.origin === window.rentsyst.crmUrl && e.data) {
				ProcessParentMessage_2( e.data ); // e.data hold the message
			}
		} , false);

		function ProcessParentMessage_2(message) {
			sendAjax('save-info', message, function (message) {
				$('#status-no-connect').css('display', 'none');
				$('#status-connect').css('display', 'block');
				displayVehicleSyncBlock();
				displayBoxSetting();

				if(message.login_url) {
					$('#link_to_login_rentsyst').attr('href', message.login_url);
				}
			});
		}

		function sendAjax(action, message, callback) {
			var controller = getUrlParam('page');
			var buildAction = buildFullAction(controller, action);
			$.post({
				url: ajaxurl,
				data: {
					action: buildAction,
					message: message
				},
				success: function (responseJson) {
					let response = JSON.parse(responseJson);
					if(response.status === 'error') {
						loader(false);
						alert(response.message);
					}
					callback(response);
				},
				error: function () {
					loader(false);
					alert('Error on the server, please try again');
				}
			});
		}

		function getUrlParam(parameter, defaultvalue){
			var urlparameter = defaultvalue;
			if(window.location.href.indexOf(parameter) > -1){
				urlparameter = getUrlVars()[parameter];
			}
			return urlparameter;
		}

		function buildFullAction(controller, action) {
			return pluginPrefix + '/' + controller + '/' + action;
		}

		function getUrlVars() {
			var vars = {};
			window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
				vars[key] = value;
			});
			return vars;
		}

		function displayVehicleSyncBlock() {
			$('#status-upload-vehicles').css('display', 'inherit');
		}

		function displayBoxSetting() {
			$('.ibox-content').css('display', 'inherit');
		}

		function hiddenVehicleSyncBlock() {
			$('#status-upload-vehicles').css('display', 'none');
		}

		function hiddenBoxSetting() {
			$('.ibox-content').css('display', 'none');
		}

		function uploadVehicle(url, callable) {
			loader('Data loading...');
			sendAjax(url, {}, function (result) {
				if(result.imagesForUpload) {
					loadImages(result.imagesForUpload, result.imagesForUpload.length);
				}
				loader(false);
				if(result.date) {
					callable(result.date);
				}
			});
		}

		function loadImages(images, length) {
			let item = images.shift();
			if(item) {
				loader('Loading images: ' + (length - images.length) + ' from ' + length);
				sendAjax('load-images', item, function( ) {
					loadImages(images, length);
				})
			} else {
				loader( false );
			}
		}


		function loader(message) {
			if(message) {
				$('.rentsyst-loading').removeClass('hidden');
				$('.loader-message').html(message);
			} else {
				$('.rentsyst-loading').addClass('hidden');
				$('.loader-message').html('');
			}
		}

		window.rentsystLoadNewVehicleParams = function( params ) {
			sendAjax('update-vehicle-params', params, function( result ) {
				console.log(result);
			});
		}

	});

})( jQuery );
