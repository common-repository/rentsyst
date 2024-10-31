<?php

namespace rentsyst\admin;

use Exception;
use rentsyst\admin\components\Rentsyst_CatalogVehicles;
use rentsyst\admin\components\Rentsyst_Filter_Widget;
use rentsyst\admin\components\Rentsyst_FormSearch_Widget;
use rentsyst\admin\components\Rentsyst_Widget;
use rentsyst\admin\controllers\Rentsyst_CatalogController;
use rentsyst\admin\controllers\Rentsyst_DesignController;
use rentsyst\admin\controllers\Rentsyst_DesignV2Controller;
use rentsyst\admin\controllers\Rentsyst_RentsystController;
use rentsyst\includes\Rentsyst_PluginSettings;
use rentsyst\includes\UrlConfig;
use rentsyst\site\Rentsyst_BookingWidget;
use rentsyst\site\Rentsyst_ResourceLoader;
use WP_REST_Request;
use function add_submenu_page;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Rentsyst
 * @subpackage rentsyst/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rentsyst
 * @subpackage rentsyst/admin
 * @author     Your Name <email@example.com>
 */
class Rentsyst_Admin
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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version )
	{
		$this->version = $version;
		$this->plugin_name = $plugin_name;
		$this->registerNewShortcode();

	}

	public function registerNewShortcode()
	{
		require_once WP_RENTSYST_PLUGIN_DIR . '/includes/shortcodes/booking.php';

		require_once WP_RENTSYST_PLUGIN_DIR . '/includes/shortcodes/payment.php';

		require_once WP_RENTSYST_PLUGIN_DIR . '/includes/shortcodes/catalog.php';

		require_once WP_RENTSYST_PLUGIN_DIR . '/includes/shortcodes/company.php';
	}

	public function init()
	{
		if ( Rentsyst_PluginSettings::isCatalogVehiclesEnable() && get_option( 'rentsyst_auth' ) ) {
			Rentsyst_CatalogVehicles::create();
		}

		add_filter( 'display_post_states', function ($post_states, $post) {
			if( $post->post_name == 'rentsyst_booking_page' ) {
				$post_states[] = 'Rentsyst booking';
			}
			if( $post->post_name == 'single-vehicle' ) {
				$post_states[] = 'Rentsyst vehicle template';
			}
			if( $post->post_name == 'catalog' ) {
				$post_states[] = 'Rentsyst catalog template';
			}

			return $post_states;
		}, 10, 2 );


		if(isset($_GET['rentsyst-iframe'])) {
			wp_head();
			$settings = $_GET['params'];
			$settings['tabs'] = $settings['tabs'] ? 1 : ! 1;
			echo Rentsyst_BookingWidget::widget($settings);
			Rentsyst_ResourceLoader::registerAll();
			echo '<style>body { margin: 0px!important; max-width: 100%!important; padding:0px!important; }</style>';
			$this->enqueue_scripts();;
			wp_footer();
			wp_die();
		}
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rentsyst_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rentsyst-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_google_fonts', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&family=Roboto:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap', [], $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_fico', dirname( plugin_dir_url( __FILE__ ) ) . '/resources/css/fico.css', [], $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '_swiper', dirname( plugin_dir_url( __FILE__ ) ) . '/resources/css/swiper.css', [], $this->version, 'all' );

		if(isset($_GET['page']) && $_GET['page'] === 'rentsyst-design' || isset($_GET['rentsyst-iframe']) ) {
			wp_enqueue_style( $this->plugin_name . '_static_chunk', dirname( plugin_dir_url( __FILE__ ) ) . '/resources/static/css/2.chunk.css', [], $this->version, 'all' );
			} else {
			wp_enqueue_style( $this->plugin_name . '_static_chunk', dirname( plugin_dir_url( __FILE__ ) ) . '/resources-v2/static/css/main.css', [], $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rentsyst-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'rentsyst', [
			'crmUrl' => UrlConfig::getCrmUrl(),
		] );

		if(isset($_GET['page']) && $_GET['page'] === 'rentsyst-design' || isset($_GET['rentsyst-iframe']) ) {
			wp_enqueue_script( $this->plugin_name . '_google_maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAddwFzEu83xzv_3kQjwLOrK3d35bmiOKg&libraries=places', [], $this->version, true );
			wp_enqueue_script( $this->plugin_name . '_tinymce', dirname( plugin_dir_url( __FILE__ ) ) . '/resources-v1/tinymce/tinymce.min.js', [], $this->version, true );
			wp_enqueue_script( $this->plugin_name . '_bundle', dirname( plugin_dir_url( __FILE__ ) ) . '/resources-v1/static/js/bundle_admin_.js', [$this->plugin_name . '_tinymce'], $this->version, false );
		}

		if(isset($_GET['page']) && $_GET['page'] === 'rentsyst-design-v2' || isset($_GET['rentsyst-iframe-v2']) ) {
			wp_enqueue_script( $this->plugin_name . '_google_maps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAddwFzEu83xzv_3kQjwLOrK3d35bmiOKg&libraries=places', [], $this->version, true );
			wp_enqueue_script( $this->plugin_name . '_bundle', dirname( plugin_dir_url( __FILE__ ) ) . '/resources-v2/static/js/bundle_admin_.js', [], $this->version, ['in_footer' => true] );
		}
	}

	public function add_menu_page()
	{
		add_menu_page( 'Rentsyst', 'Rentsyst', 'administrator', 'rentsyst', [
			$this,
			'add_connector_page'
		], 'dashicons-welcome-view-site', 30 );

		if(Rentsyst_PluginSettings::isDesignV2Enable()) {
			add_submenu_page( 'rentsyst', 'design', 'Design (new)', 'administrator', 'rentsyst-design-v2', [
				$this,
				'add_design_page_v2'
			], 20 );
		} else {
			add_submenu_page( 'rentsyst', 'design', 'Design', 'administrator', 'rentsyst-design', [
				$this,
				'add_design_page'
			], 20 );
		}

		add_submenu_page( 'rentsyst', 'Catalog Options', 'Catalog Options', 'administrator', 'catalog-options', [
			$this,
			'add_catalog_options_page'
		], 40 );

	}

	public function add_connector_page()
	{
		echo ( new Rentsyst_RentsystController() )->index();
	}

	public function add_design_page()
	{
		echo ( new Rentsyst_DesignController() )->index();
	}

	public function add_design_page_v2()
	{
		echo ( new Rentsyst_DesignV2Controller() )->index();
	}

	public function add_catalog_options_page()
	{
		echo ( new Rentsyst_CatalogController() )->index();
	}

	public function wp_rest_handler(WP_REST_Request $request)
	{
		$version = $request->get_param('version');

		if($version === 'v2') {
			$controller = new Rentsyst_DesignV2Controller();
		} else {
			$controller = new Rentsyst_DesignController();
		}

		return $controller->ajaxSave($request->get_json_params());
	}

	public function wp_ajax_handler()
	{
		$action = $_POST['action'] ? sanitize_text_field($_POST['action']) : sanitize_text_field($_GET['action']);
		$routes = explode( '/', $action );
		$routes = array_values( array_filter( $routes ) );
		if ( $routes[0] !== $this->plugin_name || count( $routes ) !== 3 ) {
			throw new Requests_Exception_HTTP_404();
		}

		$controllerName = 'rentsyst\admin\controllers\\' . 'Rentsyst_' . ucfirst( $routes[1] ) . 'Controller';

		$actionName = $this->transformAjaxMethodName( $routes[2] );

		try {
			$controller = new $controllerName;
			$response   = call_user_func( [ $controller, $actionName ] );
		} catch ( Exception $exception ) {
			return false;
		}

		if ( $response ) {
			$response            = (array) $response;
			$response['success'] = true;
			wp_send_json( $response );
		} else {
			wp_send_json_error();
		}
	}

	private function transformAjaxMethodName( $actionName )
	{
		$parts           = explode( '-', $actionName );
		$transformResult = 'ajax';
		foreach ( $parts as $part ) {
			$transformResult .= ucfirst( $part );
		}

		return $transformResult;
	}

	public function register_new_widget()
	{
		register_widget( Rentsyst_Widget::class );
		register_widget( Rentsyst_Filter_Widget::class );
		register_widget( Rentsyst_FormSearch_Widget::class );

		Rentsyst_Widget::addBlockType();
		Rentsyst_FormSearch_Widget::addBlockType();

	}

}
