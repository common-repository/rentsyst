<?php

add_filter( 'block_categories_all', function ( $categories )
{

	return array_merge( [
		[
			'slug'  => 'rentsyst',
			'title' => 'Rentsyst',
		]
	], $categories );
} );

require_once dirname( __FILE__ ) . '/vehicle-characteristics/index.php';

require_once dirname( __FILE__ ) . '/vehicle-detail-button/index.php';

require_once dirname( __FILE__ ) . '/vehicle-reservation-button/index.php';
