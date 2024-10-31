<?php

require_once WP_RENTSYST_PLUGIN_DIR . '/includes/Rentsyst_PluginSettings.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/includes/Rentsyst_CatalogFilter.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/public/Rentsyst_TemplateLoader.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/public/Rentsyst_PaymentStatusWidget.php';
require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_Language.php';

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Rentsyst
 * @subpackage rentsyst/public
 * @author     Your Name <email@example.com>
 */
class Rentsyst_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version )
	{

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	public function init()
	{
		if(!get_option(RS_Company::COMPANY_SETTINGS_ID)) {
			return false;
		}

		add_filter( 'the_content', function ( $content )
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
					$view->fullPath = WP_RENTSYST_PLUGIN_DIR . '/public/templates/rentsyst-catalog.php';
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
					$view->fullPath = WP_RENTSYST_PLUGIN_DIR . '/public/templates/rentsyst-single-vehicle.php';
				}
					Rentsyst_ResourceLoader::registerAll();
				$content           = $view->render( null, [ 'content' => $content ] );
			}

			$rentsyst_catalog_output_flag = false;
			\wp_reset_query();
			return $content;
		} );

//		add_filter( 'template_include', array( TemplateLoader::class, 'init' ) );
//		add_action( 'template_rePrice fordirect',  array( TemplateLoader::class, 'redirect' ) );
		add_filter( 'wp_list_pages_excludes', array( __CLASS__, 'exclude_service_page_from_menu' ) );

		add_action( 'pre_get_posts', function ( $context )
		{
			if ( $context->is_post_type_archive( [ 'vehicle' ] ) ) {
				new Rentsyst_CatalogFilter();
			}
		} );


//		add_filter('post_thumbnail_html', function (  $html, $postId, $size) {
//			if(get_post_type($postId) !== Rentsyst_CatalogVehicles::POST_TYPE_ID) {
//				return $html;
//			}
//
//			$images = get_post_meta($postId, 'attachments', true);
//
//			if(count($images) < 2) {
//				return $html;
//			}
//
//			$result = '<div class="rentsyst-image-group-wrapper">';
//			$result .= '<div class="rentsyst-gallery-preview-wrapper">';
//
//			foreach ($images as $image_id) {
//				$result .= '<div class="rentsyst-gallery-image-item" data-img-url-set="' . wp_get_attachment_image_srcset($image_id, $size) . '" data-img-url="' . wp_get_attachment_image_url($image_id, $size) . '"></div>';
//			}
//
//			$result .= '</div>';
//			$result .= $html;
//			$result .= '</div>';
//			return $result;
//		}, 10, 3);
	}

	public static function exclude_service_page_from_menu( $exclude_array )
	{
		array_push( $exclude_array, get_option( 'rentsyst_confirmation_page_id' ), get_option( Rentsyst_PluginSettings::CATALOG_SINGLE_PAGE_ID ) );

		return $exclude_array;
	}

	public function add_booking_button()
	{
		if ( ! get_option( 'rentsyst_enable_booking_button' ) || Rentsyst_BookingWidget::$is_display ) {
			if ( $page_id = get_option( Rentsyst_PluginSettings::BOOKING_PAGE_ID ) ) {

				$page_id = Rentsyst_Language::getConnectedPagesId($page_id)[Rentsyst_Language::getLanguageCode()] ?? $page_id;
				$booking_page_url = get_permalink( $page_id );

				echo "<script>window.rsBokingPageUrl = '$booking_page_url'</script>";
			}

			return false;
		}

		Rentsyst_ResourceLoader::registerAll();
		$settings = Rentsyst_PluginSettings::getSettings();
		require_once plugin_dir_path( __FILE__ ) . 'partials/booking-button.php';
	}

	public function wp_rest_get_token_handler()
	{
		nocache_headers();
		return [
			'token' => Rentsyst_Api::getInstance()->getToken(),
			'status' => 'success'
		];
	}

	public function wp_ajax_handler()
	{
		echo 'test';

		wp_die();
	}


}
