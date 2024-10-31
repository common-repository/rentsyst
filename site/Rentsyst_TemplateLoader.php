<?php

namespace rentsyst\site;

use rentsyst\admin\components\Rentsyst_CatalogVehicles;
use rentsyst\includes\Rentsyst_PluginSettings;

class TemplateLoader
{
	public function init($template)
	{
		if ( is_embed() ) {
			return $template;
		}

		$default_file = self::get_template_loader_default_file();

		if ( $default_file ) {
			/**
			 * Filter hook to choose which files to find before WooCommerce does it's own logic.
			 *
			 * @since 3.0.0
			 * @var array
			 */
			$search_files = self::get_template_loader_files( $default_file );
			$template     = locate_template( $search_files );

			if ( ! $template ) {
				$template = WP_RENTSYST_PLUGIN_DIR . '/site/templates/' . $default_file;
			}

		}

		return $template;
	}

	private static function get_template_loader_default_file()
	{
		if ( is_singular( Rentsyst_CatalogVehicles::POST_TYPE_ID ) ) {
			$default_file = 'single-' . Rentsyst_CatalogVehicles::POST_TYPE_ID . '.php';
			Rentsyst_ResourceLoader::registerAll();
		} elseif ( is_tax( get_object_taxonomies( Rentsyst_CatalogVehicles::POST_TYPE_ID ) ) ) {
			$object = get_queried_object();

			if ( is_tax( Rentsyst_CatalogVehicles::TAXONOMY_ID ) ) {
				$default_file = 'taxonomy-' . $object->taxonomy . '.php';
			} else {
				$default_file = 'archive-' . Rentsyst_CatalogVehicles::POST_TYPE_ID . '.php';
			}
		} elseif ( is_post_type_archive( Rentsyst_CatalogVehicles::POST_TYPE_ID )  ) {
			$default_file = 'archive-' . Rentsyst_CatalogVehicles::POST_TYPE_ID . '.php';
			Rentsyst_ResourceLoader::registerAll();
		} else {
			$default_file = '';
		}
		return $default_file;
	}

	private static function get_template_loader_files( $default_file ) {
		$templates   = apply_filters( 'rentsyst_template_loader_files', array(), $default_file );
		$templates[] = 'rentsyst.php';

		if ( is_page_template() ) {
			$templates[] = get_page_template_slug();
		}

		if ( is_singular( Rentsyst_CatalogVehicles::POST_TYPE_ID ) ) {
			the_post();
			$object       = get_queried_object();
			$name_decoded = urldecode( $object->post_name );
			if ( $name_decoded !== $object->post_name ) {
				$templates[] = "single-" . Rentsyst_CatalogVehicles::POST_TYPE_ID . "-{$name_decoded}.php";
			}
			$templates[] = "single-" . Rentsyst_CatalogVehicles::POST_TYPE_ID . "-{$object->post_name}.php";
		}

		if ( is_tax( get_object_taxonomies( Rentsyst_CatalogVehicles::POST_TYPE_ID ) ) ) {
			$object      = get_queried_object();
			$templates[] = 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
			$templates[] = WP_RENTSYST_PLUGIN_DIR . '/site/templates/' . 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
			$templates[] = 'taxonomy-' . $object->taxonomy . '.php';
			$templates[] = WP_RENTSYST_PLUGIN_DIR . '/site/templates/' . 'taxonomy-' . $object->taxonomy . '.php';
		}

		$templates[] = $default_file;
		$templates[] = WP_RENTSYST_PLUGIN_DIR . '/site/templates/' . $default_file;

		return array_unique( $templates );
	}

	public function redirect()
	{
		global $wp_query, $wp;

		// When default permalinks are enabled, redirect shop page to post type archive url.
		if ( ! empty( $_GET['page_id'] ) && '' === get_option( 'permalink_structure' ) && absint(get_option( Rentsyst_PluginSettings::CATALOG_PAGE_ID )) === absint( $_GET['page_id'] ) && get_post_type_archive_link( Rentsyst_CatalogVehicles::POST_TYPE_ID ) ) {
			wp_safe_redirect( get_post_type_archive_link( Rentsyst_CatalogVehicles::POST_TYPE_ID ) );
			exit;
		}
	}
}
