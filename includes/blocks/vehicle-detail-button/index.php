<?php

add_action( 'init', function () {
	register_block_type( __DIR__ . '/static', [
		'render_callback' => function($attributes, $content, $block) {
			if ( ! isset( $block->context['postId'] ) ) {
				return '';
			}

			$post_ID            = $block->context['postId'];

			return str_replace('<a class', '<a href="' . get_the_permalink($post_ID) . '" class', $content);
		}
	] );
} );
