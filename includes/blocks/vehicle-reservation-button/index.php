<?php

add_action( 'init', function () {
	register_block_type( __DIR__ . '/static', [
		'render_callback' => function($attributes, $content, $block) {
			if ( ! isset( $block->context['postId'] ) ) {
				return '';
			}

			Rentsyst_ResourceLoader::registerAll();

			$post_ID            = $block->context['postId'];

			return str_replace('<a class="', '<a data-id="' . get_post_meta($post_ID, 'id', true) . '" class="rentsyst-booking ', $content);
		}
	] );
} );
