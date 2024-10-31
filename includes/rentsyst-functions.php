<?php

use rentsyst\includes\RS_Vehicle;

require_once WP_RENTSYST_PLUGIN_DIR . '/includes/RS_Vehicle.php';

function rentsyst_get_template_part( $slug, $name = '', $args = [] ) {
	$cache_key = sanitize_key( implode( '-', array( 'template-part', $slug, $name ) ) );
	$template  = (string) wp_cache_get( $cache_key, 'rentsyst' );

	if ( ! $template ) {
		if ( $name ) {
			$template = locate_template(
				array(
					"{$slug}-{$name}.php",
					"rentsyst/{$slug}-{$name}.php",
				)
			);

			if ( ! $template ) {
				$fallback = WP_RENTSYST_PLUGIN_DIR . "/site/templates/{$slug}-{$name}.php";
				$template = file_exists( $fallback ) ? $fallback : '';
			}
		}

		if ( ! $template ) {
			// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
			$template = locate_template(
				array(
					"{$slug}.php",
					"rentsyst/{$slug}.php",
				)
			);
		}

		wp_cache_set( $cache_key, $template, 'rentsyst' );
	}

	// Allow 3rd party plugins to filter template file from their plugin.

	if ( $template ) {
		load_template( $template, false, $args);
	}
}

function rentsyst_setup_vehicle_data( $post ) {
	unset( $GLOBALS['rentsyst_vehicle'] );

	if ( is_int( $post ) ) {
		$post = get_post( $post );
	}

	if ( empty( $post->post_type ) || ! in_array( $post->post_type, array( 'vehicle' ), true ) ) {
		return;
	}

	$GLOBALS['rentsyst_vehicle'] = new RS_Vehicle( $post );

	return $GLOBALS['rentsyst_vehicle'];
}
add_action( 'the_post', 'rentsyst_setup_vehicle_data' );
