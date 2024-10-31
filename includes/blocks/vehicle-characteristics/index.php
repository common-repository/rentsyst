<?php
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function rentsyst_block_post_custom_field_rentsyst_block_post_custom_field_block_init() {
	register_block_type( __DIR__ . '/static', [
		'render_callback' => 'render_custom_field'
	] );
}

function render_custom_field($attributes, $content, $block) {
	if ( ! isset( $block->context['postId'] ) ) {
		return '';
	}

	$selected = $attributes['selectedField'];

	$post_ID            = $block->context['postId'];

	$fieldValue = get_post_meta($post_ID, $selected, true);

	if($selected === 'options_data') {
		$fieldValue = displayOptions($fieldValue, $attributes['optionsSetting']);
	} elseif ($selected === 'color') {
		$fieldValue = "<div class='rentsyst-circle-color' style='background: #" . $fieldValue . "'></div>";
	}


	$tag_name         = 'h2';
	$align_class_name = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";

	if ( isset( $attributes['level'] ) ) {
		$tag_name = 0 === $attributes['level'] ? 'p' : 'h' . $attributes['level'];
	}

	if ( isset( $attributes['isLink'] ) && $attributes['isLink'] ) {
		$rel   = ! empty( $attributes['rel'] ) ? 'rel="' . esc_attr( $attributes['rel'] ) . '"' : '';
		$fieldValue = sprintf( '<a href="%1$s" target="%2$s" %3$s>%4$s</a>', get_the_permalink( $post_ID ), esc_attr( $attributes['linkTarget'] ), $rel, $fieldValue );
	}
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $align_class_name ) );

	return sprintf(
		'<%1$s %2$s>%3$s</%1$s>',
		$tag_name,
		$wrapper_attributes,
		$fieldValue
	);

}

function displayOptions($optionsJson, $settings) {
	$currentOptions = json_decode($optionsJson);

	$listOfOptions = fillListOfOption($currentOptions, $settings);

	$result = '';

	if($settings['displayType'] === 'list') {
		$result .= '<ul class="rentsyst-option-list">';
		foreach ($listOfOptions as $option) {
			$result .= '<li>';
			if($settings['displayIcon']) {
				$result .= '<i class="fico rentsyst-option-icon fico-' . $option['icon'] . '" title="' . $option['name'] . '"></i>';
			}
			if(!$settings['hideTitle']) {
				$result .= $option['name'];
			}
			$result .= '</li>';
		}
		$result .= '</ul>';
	} else {
		$result .= '<div class="rentsyst-options_wrap">';
		foreach ($listOfOptions as $key => $option) {
			if($settings['displayIcon']) {
				$result .= '<i class="fico rentsyst-option-icon fico-' . $option['icon'] . '" title="' . $option['name'] . '"></i>';
			}
			if(!$settings['hideTitle']) {
				$result .= $option['name'];
			}
			if($settings['itemDelimiter'] && $key !== $settings['limit'] - 1) {
				$result .= $settings['itemDelimiter'];
			}
		}
		$result .= '</div>';
	}

	return $result;
}

function fillListOfOption($currentOptions, $settings) {
	$result = [];
	foreach ($settings['activeOptions'] as $activeOption) {
		foreach ($currentOptions as $option) {
			if($activeOption === $option->id) {
				$result[] = [
					'name' => $option->name,
					'icon' => $option->icon,
				];

				if(count($result) === $settings['limit']) {
					return $result;
				}
			}
		}
	}

	return $result;
}

add_action( 'init', 'rentsyst_block_post_custom_field_rentsyst_block_post_custom_field_block_init' );

add_action( 'init', 'register_meta_rentsyst');


function register_meta_rentsyst() {
	$fields = [
		'discount_price',
		'id',
		'year',
		'number_seats',
		'number_doors',
		'large_bags',
		'small_bags',
		'brand',
		'mark',
		'group',
		'color',
		'body_type',
		'min_price',
		'price',
		'periods_price',
		'volume_tank',
		'insurance_deposit',
		'volume_engine',
		'fuel',
		'transmission',
		'locations',
		'thumbnail',
		'photos',
		'odometer',
		'link',
		'options_data',
	];

	foreach ($fields as $field) {
		rentsyst_register_meta_field($field);
	}

}

function rentsyst_register_meta_field(string $fieldName)
{
	register_post_meta(
		'vehicle',
		$fieldName,
		[
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		]
	);
}
