<?php

use rentsyst\admin\components\Rentsyst_View;
use rentsyst\includes\Rentsyst_CatalogFilter;
use rentsyst\includes\RS_Company;
use rentsyst\site\Rentsyst_ResourceLoader;

require_once WP_RENTSYST_PLUGIN_DIR . '/includes/RS_Company.php';

add_shortcode( 'rentsyst_catalog_img', function ()
{
	global  $rentsyst_vehicle;
	if($rentsyst_vehicle) {
		return '<img src="' . $rentsyst_vehicle->getThumbnailUrl() . '" alt="' . $rentsyst_vehicle->getMark() . ' ' . $rentsyst_vehicle->getBrand() . '">';
	}
} );

add_shortcode( 'rentsyst_catalog_vehicle_link', function ()
{
	global  $rentsyst_vehicle;
	if($rentsyst_vehicle) {
		return str_replace(['https://', 'http://'], '', $rentsyst_vehicle->getLink());
	}
} );

add_shortcode( 'rentsyst_catalog_vehicle_link_button', function ()
{
	global  $rentsyst_vehicle;
	if($rentsyst_vehicle) {
		return str_replace(get_option( 'home' ), '', $rentsyst_vehicle->getLink());
	}
} );

add_shortcode( 'rentsyst_catalog_title', function ()
{
	 global  $rentsyst_vehicle;
	if($rentsyst_vehicle) {
		return $rentsyst_vehicle->getBrand() . ' ' . $rentsyst_vehicle->getMark();
	}
} );

add_shortcode( 'rentsyst_catalog_min_price', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
	 	if($discount_price = $rentsyst_vehicle->getDiscountPrice()) {
	 	    	return '<span class="rentsyst-discount-price">' . $discount_price . '</span>' . '<span class="rentsyst-discount"><span class="rentsyst-discount-old-price">' . $rentsyst_vehicle->getMinPrice() . '</span></span>';
	    }
	 	return $rentsyst_vehicle->getMinPrice();
	 }
} );

add_shortcode( 'rentsyst_catalog_price', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
	 	if($discount_price = $rentsyst_vehicle->getDiscountPrice()) {
	 	    	return '<span class="rentsyst-discount-price">' . $discount_price . '</span>' . '<span class="rentsyst-discount"><span class="rentsyst-discount-old-price">' . $rentsyst_vehicle->getPrice() . '</span></span>';
	    }
	 	return $rentsyst_vehicle->getPrice();
	 }
} );

add_shortcode( 'rentsyst_catalog_period_prices', function ($args)
{
	$limit = $args['limit'] ?? 100;
	$start = $args['offset'] ?? 0;
	$output = '';
	global  $rentsyst_vehicle;
	if($rentsyst_vehicle) {
		 $basePrice = (float) $rentsyst_vehicle->getPrice();
		 $periods = $rentsyst_vehicle->getPeriodsPrice();
		 if(!$periods || !is_array($periods)) {
		 	return $output;
		 }
		 usort($periods, function ($element, $nextElement) {
		 	return $element->period_from > $nextElement->period_from;
		 });
		 foreach ($periods as $key => $period) {
		 	if($key < $start || $key > $limit) {
		 		continue;
		    }
		 	$currentPrice = number_format($basePrice + $basePrice * $period->discount / 100, 2, '.', '');
		 	$currency = RS_Company::getInstance()->getCurrency();
		 	$output .= "<div class=\"wp-block-group rentsyst-price-per-day\"><div class=\"wp-block-group__inner-container\">";
		 	$output .= "<p class=\"has-text-color has-text-align-right has-small-font-size has-cyan-bluish-gray-color\">{$period->name} {$period->type}</p>";
		 	$output .= "<p style=\"font-size:19px\" class=\"has-text-align-right\"><strong>{$currentPrice} {$currency}/{$period->type}</strong></p>";
		 	$output .= "</div></div>";
		 }
	 }
	return $output;
} );

add_shortcode( 'rentsyst_catalog_category', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getGroup();
	 }
} );

add_shortcode( 'rentsyst_catalog_transmission', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getTransmission();
	 }
} );

add_shortcode( 'rentsyst_catalog_passengers', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getNumberSeats();
	 }
} );

add_shortcode( 'rentsyst_catalog_luggage', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getLargeBags();
	 }
} );

add_shortcode( 'rentsyst_catalog_body_type', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getBodyType();
	 }
} );

add_shortcode( 'rentsyst_catalog_year', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getYear();
	 }
} );

add_shortcode( 'rentsyst_catalog_fuel', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getFuel();
	 }
} );

add_shortcode( 'rentsyst_catalog_doors', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getNumberDoors();
	 }
} );

add_shortcode( 'rentsyst_catalog_color_badge', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getColorBadge();
	 }
} );

add_shortcode( 'rentsyst_catalog_odometer', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getOdometer();
	 }
} );

add_shortcode( 'rentsyst_catalog_options', function ($args)
{
	$delimiter = $args['delimiter'] ?? ', ';
	$output = '';
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 $options = $rentsyst_vehicle->getOptions() ?? [];
		 $count = count((array) $options);
	 	foreach ($options as $key => $option) {
	 		$output .= $option;
	 		if($key !== $count-1) {
	 			$output .= $delimiter;
		    }
	    }
		 return $output;
	 }
} );

add_shortcode( 'rentsyst_catalog_description', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getDescription();
	 }
} );

add_shortcode( 'rentsyst_catalog_comments', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		 return $rentsyst_vehicle->getComment();
	 }
} );


add_shortcode( 'rentsyst_catalog_slider', function ()
{
	 global  $rentsyst_vehicle;
	 if($rentsyst_vehicle) {
		$slides  = '';
		foreach ($rentsyst_vehicle->getPhotoUrls() as $photo) {
			$slides .= "<a href=\" $photo\" class=\"thumbnail\" data-fancybox=\"preview\">
							<img src=\" $photo\">
						</a>";
		}
	 	$html = " <div class=\"gallery\">
            <a data-trigger=\"preview\" class=\"gallery__main\" href=\"javascript:\"><img
                        src=\"{$rentsyst_vehicle->getThumbnailUrl()}\" alt=\"car1\">
            </a>
            <div class=\"gallery__thumbnails\">
			    $slides
            </div>

        </div>";
		 return $html;
	 }
} );


add_shortcode( 'rentsyst_catalog_filter', function ()
{
	return Rentsyst_CatalogFilter::display();
} );



add_shortcode( 'rentsyst_catalog', function ()
{
	$view             = new Rentsyst_View();
	$view->fullPath = locate_template([
		'rentsyst-catalog.php',

	]);
	if(!$view->fullPath) {
		$view->fullPath = WP_RENTSYST_PLUGIN_DIR . '/site/templates/rentsyst-catalog.php';
	}
	global /** @var WP_Query $wp_query */
	$wp_query;

	$saveOldQuery = $wp_query;
	$queryArgs = [
		'post_type' => 'vehicle',
		'meta_query' => array_merge(['relation' => 'AND'], Rentsyst_CatalogFilter::getParams()),
		'paged' => get_query_var( 'paged' ),
		'meta_key' => 'min_price',
		'orderby'  => [ 'meta_value_num'=>'ASC' ],
	];
	$queryArgs = apply_filters('rentsyst_catalog_query', $queryArgs);
	$wp_query = new WP_Query( $queryArgs );
	Rentsyst_ResourceLoader::registerAll();
	$content           = $view->render( null, [ 'found_posts' => $wp_query->found_posts ] );
	$wp_query = $saveOldQuery;
	Rentsyst_CatalogFilter::display();
	return $content;
} );
