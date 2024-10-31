<?php

namespace rentsyst\admin\components;

use rentsyst\includes\Rentsyst_CatalogFilter;
use rentsyst\includes\Rentsyst_PluginSettings;
use WP_Post;

class Rentsyst_CatalogVehicles
{
	const POST_TYPE_ID = 'vehicle';
	const TAXONOMY_ID = 'vehicles_group';

	private static $instance;
	private function __construct()
	{
		$this->registerTaxonomy();
		$this->registerPostType();
		$this->addCustomColumn();
		$this->copyMetaValueFromTemplatePages();
	}

	public static function create()
	{
		if(!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function registerTaxonomy()
	{
		register_taxonomy( self::TAXONOMY_ID, [ self::POST_TYPE_ID ], [
			'label'                 => 'Groups',
			'labels'                => [
				'name'              => 'Groups',
				'singular_name'     => 'Group',
				'search_items'      => 'Search Group',
				'all_items'         => 'All Groups',
				'view_item '        => 'View Group',
				'edit_item'         => 'Edit Genre',
				'update_item'       => 'Update Genre',
				'add_new_item'      => 'Add New Genre',
				'new_item_name'     => 'New Genre Name',
				'menu_name'         => 'Groups',
			],
			'description'           => '',
			'public'                => true,
			'hierarchical'          => false,
			'rewrite'               => true,
			'capabilities'          => array('edit_terms' => false),
			'meta_box_cb'           => null,
			'show_admin_column'     => false,
			'show_in_rest'          => null,
			'rest_base'             => null,
		] );
	}

	private function registerPostType()
	{
		register_post_type(self::POST_TYPE_ID, array(
			'label'  => 'Vehicles',
			'labels' => array(
				'name'               => 'Vehicles',
				'singular_name'      => 'Vehicle',
				'add_new'            => 'Add Vehicle',
				'menu_name'          => 'Vehicles',
			),
			'description'         => '',
			'public'              => true,
			'show_in_menu'        => true,
			'menu_position'       => 30,
			'menu_icon'           => 'dashicons-performance',
			'map_meta_cap' => true,
			'capabilities' => array(
				'edit_post' => true,
				'create_posts' => false,
			),
			'hierarchical'        => false,
			'supports'            => [ 'title', 'editor', 'comments', 'custom-fields', 'thumbnail'],
			'taxonomies'          => ['rentsyst_vehicles_group'],
			'has_archive'         => true,
			'rewrite'             => true,
			'query_var'           => true,
			'show_in_rest' => true,
		) );
	}

	private function addCustomColumn()
	{
		add_filter( 'manage_' . self::POST_TYPE_ID . '_posts_columns', function ($columns) {
			unset( $columns['author'] );
			$columns['year'] = __( 'Year', 'rentsyst' );
			$columns['color'] = __( 'Color', 'rentsyst' );
			$columns['thumbnail'] = __( 'Photo', 'rentsyst' );
			$columns['group'] = __( 'Group', 'rentsyst' );
			$columns['body_type'] = __( 'Body type', 'rentsyst' );
			$columns['fuel'] = __( 'Fuel', 'rentsyst' );
			$columns['transmission'] = __( 'Transmission', 'rentsyst' );
			$columns['price'] = __( 'Base price', 'rentsyst' );
			$columns['number_seats'] = __( 'Number of seats', 'rentsyst' );
			$columns['large_bags'] = __( 'Large bags', 'rentsyst' );
			$columns['small_bags'] = __( 'Small bags', 'rentsyst' );

			return $columns;
		} );

		add_action( 'manage_' . self::POST_TYPE_ID . '_posts_custom_column' , function ($column, $post_id) {
			switch ( $column ) {

				case 'thumbnail' :
					echo '<img class="rentsyst_photo_thumbnails" src="'.  get_post_meta( $post_id , 'thumbnail', true ) . '">';
					break;
				case 'year' :
					echo get_post_meta( $post_id , 'year', true );
					break;
				case 'color' :
					echo '<span class="rentsyst-circle-color" style="background-color: #' . get_post_meta( $post_id , 'color', true ) . '"></span>';
					break;
				case 'group' :
					echo get_post_meta( $post_id , 'group' , true );
					break;
				case 'body_type' :
					echo get_post_meta( $post_id , 'body_type' , true );
					break;
				case 'fuel' :
					echo get_post_meta( $post_id , 'fuel' , true );
					break;
				case 'transmission' :
					echo get_post_meta( $post_id , 'transmission' , true );
					break;
				case 'price' :
					echo get_post_meta( $post_id , 'price' , true );
					break;
				case 'number_seats' :
					echo get_post_meta( $post_id , 'number_seats' , true );
					break;
				case 'number_doors' :
					echo get_post_meta( $post_id , 'number_doors' , true );
					break;
				case 'large_bags' :
					echo get_post_meta( $post_id , 'large_bags' , true );
					break;
				case 'small_bags' :
					echo get_post_meta( $post_id , 'small_bags' , true );
					break;

			}
		}, 10, 2 );

		add_filter('pre_get_posts', function ( $object ) {
			if( $object->get('post_type') !== self::POST_TYPE_ID )
				return;

			$object->set('post_status', sanitize_text_field($_GET['post_status'] ?? ''));
		});
	}

	public static function uploadImage( string $post_id )
	{
		$post_name = get_post_meta($post_id, 'brand', true) . ' ' . get_post_meta($post_id, 'mark', true);

		$photos = get_post_meta($post_id, 'photos', true);

		$attachments = [];
		foreach ($photos as $photo) {
			$attachments[] = media_sideload_image(
				$photo,
				0,
				$post_name,
				'id'
			);
		}

		update_post_meta($post_id, 'attachments', $attachments);

		$image = media_sideload_image(
			get_post_meta($post_id, 'thumbnail', true),
			$post_id,
			$post_name,
			'id'
		);
		set_post_thumbnail( $post_id, $image );
	}

	private static function deleteAllVehicles()
	{
		$allposts = get_posts( array( 'post_type' => self::POST_TYPE_ID, 'numberposts' => - 1 ) );
		foreach ( $allposts as $eachpost ) {
			wp_delete_post( $eachpost->ID, true );

		}
	}

	public static function updateVehicles($vehicles)
	{
		Rentsyst_CatalogVehicles::create();
		$filters = [];
		$prices = [];

		$imagesForUpload = [];

		$postIds = [];
		foreach ($vehicles as $vehicle) {

			$post_category = self::getTaxonomy($vehicle->group);

			$args = array(
				'meta_key' => 'id',
				'meta_value' => $vehicle->id,
				'post_type' => Rentsyst_CatalogVehicles::POST_TYPE_ID,
				'post_status' => 'any',
				'posts_per_page' => -1
			);
			$post = get_posts($args)[0] ?? null;

			if(!$post) {
				$post = array(
					'post_content' => false,
					'post_title'   =>  $vehicle->brand . ' ' . $vehicle->mark,
					'post_type'    => Rentsyst_CatalogVehicles::POST_TYPE_ID,
					'post_status'  => 'publish',
					'tax_input'      => array( self::TAXONOMY_ID => [$post_category] ),
				);
				$post_id = wp_insert_post( $post );
			} else {
				$post_id = $post->ID;
				wp_update_post([
					'ID' => $post_id,
					'tax_input'      => array( self::TAXONOMY_ID => [$post_category] ),
				]);
			}

			$postIds[] = $post_id;

			$filters['year'][sanitize_title($vehicle->year)] = $vehicle->year;
			$filters['number_seats'][sanitize_title($vehicle->number_seats)] = $vehicle->number_seats;
			$filters['number_doors'][sanitize_title($vehicle->number_doors)] = $vehicle->number_doors;
			$filters['large_bags'][sanitize_title($vehicle->large_bags)] = $vehicle->large_bags;
			$filters['small_bags'][sanitize_title($vehicle->small_bags)] = $vehicle->small_bags;
			$filters['brand'][sanitize_title($vehicle->brand)] = $vehicle->brand;
			$filters['group'][sanitize_title($vehicle->group)] = $vehicle->group;
			$filters['color'][sanitize_title($vehicle->color->code)] = sanitize_title($vehicle->color->code);
			$filters['body_type'][sanitize_title($vehicle->body_type)] = $vehicle->body_type;
			$filters['fuel'][sanitize_title($vehicle->fuel)] = $vehicle->fuel;
			$period_price = $vehicle->periods_price;
			usort($period_price, function ($element, $nextElement) {
				return $element->period_from > $nextElement->period_from;
			});
			$min_price = $vehicle->price + $vehicle->price * end($period_price)->discount / 100;
			if(isset($vehicle->seasons[0])) {
				$min_price += $min_price * $vehicle->seasons[0]->percent / 100;
			}
			$prices[] = $min_price;

			$filters['transmission'][sanitize_title($vehicle->transmission)] = $vehicle->transmission;

			delete_post_meta($post_id, 'options');
			foreach ($vehicle->options as $option) {
				add_post_meta( $post_id, 'options', $option->name );
				$filters['options'][sanitize_title($option->name)] = $option->name;
			}

			$hash = hash('md5', json_encode($vehicle));
			if($hash !== get_post_meta($post_id, 'hash', true)) {
				if(
					get_post_meta($post_id, 'thumbnail', true) !== $vehicle->thumbnail ||
				    get_post_meta($post_id, 'photos', true) !== $vehicle->photos
				) {
					$imagesForUpload[] = $post_id;
				}
				update_post_meta( $post_id, 'hash', $hash );
				update_post_meta( $post_id, 'min_price', number_format( $min_price, 2 ) );
				update_post_meta( $post_id, 'periods_price', $period_price );
				update_post_meta( $post_id, 'transmission', $vehicle->transmission );
				update_post_meta( $post_id, 'locations', $vehicle->locations );
				update_post_meta( $post_id, 'options_data', json_encode( $vehicle->options ) );
				update_post_meta( $post_id, 'discount_price', '' );
				update_post_meta( $post_id, 'id', $vehicle->id );
				update_post_meta( $post_id, 'year', $vehicle->year );
				update_post_meta( $post_id, 'number_seats', $vehicle->number_seats );
				update_post_meta( $post_id, 'number_doors', $vehicle->number_doors );
				update_post_meta( $post_id, 'large_bags', $vehicle->large_bags );
				update_post_meta( $post_id, 'small_bags', $vehicle->small_bags );
				update_post_meta( $post_id, 'brand', $vehicle->brand );
				update_post_meta( $post_id, 'mark', $vehicle->mark );
				update_post_meta( $post_id, 'group', $vehicle->group );
				update_post_meta( $post_id, 'color', sanitize_title( $vehicle->color->code ) );
				update_post_meta( $post_id, 'body_type', $vehicle->body_type );
				update_post_meta( $post_id, 'price', $vehicle->price );
				update_post_meta( $post_id, 'volume_tank', $vehicle->volume_tank );
				update_post_meta( $post_id, 'insurance_deposit', $vehicle->insurance_deposit );
				update_post_meta( $post_id, 'volume_engine', $vehicle->volume_engine );
				update_post_meta( $post_id, 'fuel', $vehicle->fuel );
				update_post_meta( $post_id, 'thumbnail', $vehicle->thumbnail );
				update_post_meta( $post_id, 'photos', $vehicle->photos );
				update_post_meta( $post_id, 'odometer', $vehicle->odometer );
				update_post_meta( $post_id, 'link', $vehicle->_links->self->href );
			}

		}
		Rentsyst_CatalogFilter::saveOptions($filters);
		Rentsyst_CatalogFilter::saveRangePrices($prices);

		$args = array(
			'post_type' => Rentsyst_CatalogVehicles::POST_TYPE_ID,
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'exclude' => implode(',', $postIds)
		);
		$oldVehicles = get_posts($args);

		/** @var WP_Post $old_vehicle */
		foreach ($oldVehicles as $old_vehicle) {
			wp_update_post([
				'ID' => $old_vehicle->ID,
				'post_status'      => 'draft',
			]);
		}
		return $imagesForUpload;
	}

	private static function getTaxonomy( $name )
	{
		$taxonomy = get_term_by('name', $name, self::TAXONOMY_ID);
		if(!$taxonomy) {
			$term_taxonomy_id = wp_insert_term($name, self::TAXONOMY_ID)['term_taxonomy_id'];
		} else {
			$term_taxonomy_id = $taxonomy->term_taxonomy_id;
		}
		return $term_taxonomy_id;
	}

	private function copyMetaValueFromTemplatePages()
	{
		add_action( 'added_post_meta', [$this, 'updateMetaValue'], 10, 4 );
		add_action( 'updated_post_meta', [$this, 'updateMetaValue'], 10, 4 );
	}

	public function updateMetaValue($meta_id, $post_id, $meta_key, $meta_value)
	{
		if($meta_key === '_wp_page_template' && (int) get_option(Rentsyst_PluginSettings::CATALOG_SINGLE_PAGE_ID) === $post_id ) {
			$args = array(
				'posts_per_page'   => -1,
				'post_type'        => Rentsyst_CatalogVehicles::POST_TYPE_ID,
				'suppress_filters' => true
			);
			$posts_array = get_posts( $args );
			foreach($posts_array as $post_array)
			{
				update_post_meta($post_array->ID, '_wp_page_template', $meta_value);
			}
		}
	}


}
