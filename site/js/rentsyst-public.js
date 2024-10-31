(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	$(document).ready(function () {

		var widgetBlock = $('.RentsystWidgetBlock');


		function eventListeners() {
			$('#rentsyst_booking_button').on('click', function () {

				if(widgetBlock.hasClass('RentsystWidgetShow')) {
					closeBookingCover();
				} else {
					openBookingCover();
				}
				return false;
			});

			$('.RentsystWidgetCover').on('click', function () {
				closeBookingCover();
			});
			$('.RentsystCloseIcon').on('click', function () {
				closeBookingCover();
			});

			$(window).on('keyup', function (e) {
				if(e.key === "Escape") {
					closeBookingCover();
				}
			});

		}

		function closeBookingCover() {
			widgetBlock.removeClass('RentsystWidgetShow');
			widgetBlock.addClass('RentsystWidgetHide');
			$('.RentsystWidgetCover').css('display', 'none');
			$('.RentsystCloseIcon').css('display', 'none');
		}

		function openBookingCover() {
			rentsystOpenBooking();
		}

		eventListeners();
	});

})( jQuery );

window.rentsystOpenBooking = function () {
	jQuery('.RentsystWidgetBlock').removeClass('RentsystWidgetHide');
	jQuery('.RentsystWidgetBlock').addClass('RentsystWidgetShow');
	jQuery('.RentsystWidgetCover').css('display', 'block');
	jQuery('.RentsystCloseIcon').css('display', 'block');
};


jQuery('.rentsyst-image-group-wrapper').on('mouseover', '.rentsyst-gallery-image-item', function () {
	let container = jQuery(this).parent().parent().children('.wp-post-image');

	let that = this;

	// jQuery('.rentsyst-gallery-preview-wrapper').addClass('cover-mode');
	// setTimeout(function(  ) {

		container.attr('src', jQuery(that).data('img-url'));
		container.attr('srcset', jQuery(that).data('img-url-set'));

		// jQuery('.rentsyst-gallery-preview-wrapper').removeClass('cover-mode');
	// }, 300);
});

jQuery('.rentsyst-image-group-wrapper').on('mouseout', '.rentsyst-gallery-image-item', function () {
	let container = jQuery(this).parent().parent().children('.wp-post-image');
	let firstElement = jQuery(this).parent().children('.rentsyst-gallery-image-item').first();
	container.attr('src', firstElement.data('img-url'));
	container.attr('srcset', firstElement.data('img-url-set'));
});

