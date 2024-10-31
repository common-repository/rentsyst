<?php

use rentsyst\site\Rentsyst_BookingWidget;
use rentsyst\site\Rentsyst_ResourceLoader;

add_shortcode( 'rentsyst_booking', function ($params)
{
	$script = '';
	if ( isset( $_GET['rentsyst-book-by-vehicle-id'] ) ) {
		$vehicleId = sanitize_text_field( $_GET['rentsyst-book-by-vehicle-id'] );
		$script = <<<JS
		document.addEventListener("rentsyst:search_ready", function () {
    		jQuery(document).ready(function() { bookCar($vehicleId) }); 		 
		});
JS;
	}
	if ( isset( $_GET['rentsyst-book-by-search-params'] ) ) {
		$location_from = sanitize_text_field( $_GET['locationFrom'] );
		$location_to = isset($_GET['locationTo']) ? sanitize_text_field( $_GET['locationTo'] ) : $location_from;
		$date_from = sanitize_text_field( $_GET['dateFrom'] );
		$date_to = sanitize_text_field( $_GET['dateTo'] );
		$script = <<<JS
		document.addEventListener("rentsyst:search_ready", function () {
			window.searchCars({
				dateFrom: "$date_from",
  				dateTo: "$date_to",
  				locationFrom: "$location_from",
  				locationTo: "$location_to",
			})		 
		});
JS;
	}

	if ((!is_main_query() || !in_the_loop()) && !isset($params['force_output'])) {
		return 'Booking page';
	}

	$widgetOutput = Rentsyst_BookingWidget::widget($params);

	wp_add_inline_script(Rentsyst_ResourceLoader::PREFIX . 'bundle_front', $script);

	return $widgetOutput;
} );

