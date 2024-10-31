<?php
namespace rentsyst\site;
use rentsyst\admin\components\Rentsyst_Api;
use rentsyst\admin\components\Rentsyst_Language;
use rentsyst\includes\Rentsyst_CatalogFilter;
use rentsyst\includes\Rentsyst_PluginSettings;
use rentsyst\includes\RS_Company;
use function add_action;

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Rentsyst
 * @subpackage rentsyst/site
 */
class Rentsyst_Site
{
	public function init()
	{
		if(!get_option(RS_Company::COMPANY_SETTINGS_ID)) {
			return false;
		}

		Rentsyst_CatalogContent::getInstance();

		add_filter( 'wp_list_pages_excludes', array( __CLASS__, 'exclude_service_page_from_menu' ) );

		add_action( 'pre_get_posts', function ( $context )
		{
			if ( $context->is_post_type_archive( [ 'vehicle' ] ) ) {
				new Rentsyst_CatalogFilter();
			}
		} );

		add_action('get_footer', [$this, 'add_booking_button']);

		add_action( 'rest_api_init', function () {
			register_rest_route( 'rentsyst/v1', '/access-token', array(
				'methods' => 'GET',
				'callback' => [$this, 'wp_rest_get_token_handler'],
				'permission_callback' => '__return_true'
			) );
		} );
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
}
