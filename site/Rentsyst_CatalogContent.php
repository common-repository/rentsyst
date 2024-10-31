<?php
namespace rentsyst\site;

use rentsyst\admin\components\Rentsyst_CatalogVehicles;
use rentsyst\admin\components\Rentsyst_Language;
use rentsyst\admin\components\Rentsyst_View;
use rentsyst\includes\Rentsyst_CatalogFilter;
use rentsyst\includes\Rentsyst_PluginSettings;
use rentsyst\includes\RS_Vehicle;
use WP_Query;
use function array_merge;
use function get_query_var;
use function in_array;
use function locate_template;
use function sanitize_text_field;
use const WP_RENTSYST_PLUGIN_DIR;

class Rentsyst_CatalogContent
{
	public static $instance = null;

	public function __construct()
	{
		add_filter( 'the_content', [$this, 'replaceContentForCatalogPages'] );
	}

	public static function getInstance()
	{

		if (null === self::$instance) {
			$instance = new static();
		}
		return $instance;
	}

	public function replaceContentForCatalogPages($content)
	{
		global $post, $rentsyst_catalog_output_flag;
		$catalog_page_id = get_option( Rentsyst_PluginSettings::CATALOG_PAGE_ID );
		$connectedPagesId = Rentsyst_Language::getConnectedPagesId($catalog_page_id);
		if ( in_array($post->ID, $connectedPagesId)) {
			if ( $rentsyst_catalog_output_flag ) {
				return false;
			}
			$rentsyst_catalog_output_flag = true;

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
				's' => sanitize_text_field($_GET['search'] ?? ''),
				'meta_key' => sanitize_text_field($_GET['sort-key'] ?? 'min_price'),
				'orderby'  => [ 'meta_value_num'=> sanitize_text_field($_GET['sort'] ?? 'ASC') ],
			];
			$queryArgs = apply_filters('rentsyst_catalog_query', $queryArgs);
			$wp_query = new WP_Query( $queryArgs );
			Rentsyst_ResourceLoader::registerAll();
			$content           = $view->render( null, [ 'content' => $content, 'found_posts' => $wp_query->found_posts ] );
			$wp_query = $saveOldQuery;
		} elseif ($post->post_type === ( Rentsyst_CatalogVehicles::POST_TYPE_ID) && !$rentsyst_catalog_output_flag) {
			$GLOBALS['rentsyst_vehicle'] = new RS_Vehicle( $post );
			$rentsyst_catalog_output_flag = true;
			$view             = new Rentsyst_View();
			$view->fullPath = locate_template([
				'rentsyst-single-vehicle.php',

			]);
			if(!$view->fullPath) {
				$view->fullPath = WP_RENTSYST_PLUGIN_DIR . '/site/templates/rentsyst-single-vehicle.php';
			}
			Rentsyst_ResourceLoader::registerAll();
			$content           = $view->render( null, [ 'content' => $content ] );
		}

		$rentsyst_catalog_output_flag = false;
		\wp_reset_query();
		return $content;
	}

}
