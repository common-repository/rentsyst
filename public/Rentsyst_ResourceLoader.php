<?php

class Rentsyst_ResourceLoader
{

	const PREFIX = "rentsyst_";
	public $version;
	public static $instance;

	public function __construct()
	{
		$this->version = RENTSYST_VERSION;
		$this->registerCSS();
		$this->registerJS();
	}

	public static function registerAll()
	{
		if(Rentsyst_PaymentStatusWidget::$is_display) {
			return false;
		}
		if(!get_option(RS_Company::COMPANY_SETTINGS_ID)) {
			return false;
		}
		if(!self::$instance) {
			self::$instance = new self();
		}
		return true;
	}

	public function registerCSS()
	{
		wp_enqueue_style( self::PREFIX . 'public', WP_RENTSYST_PLUGIN_URL . '/public/css/rentsyst-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( self::PREFIX . 'catalog', WP_RENTSYST_PLUGIN_URL . '/public/css/catalog.css', array(), $this->version, 'all' );
		wp_enqueue_style( self::PREFIX . 'fancybox', WP_RENTSYST_PLUGIN_URL . '/public/css/fancybox.css', array(), $this->version, 'all' );
		wp_enqueue_style( self::PREFIX . 'fico', WP_RENTSYST_PLUGIN_URL . '/resources/css/fico.css', array(), $this->version, 'all' );
		wp_enqueue_style( self::PREFIX . 'swiper', WP_RENTSYST_PLUGIN_URL . '/resources/css/swiper.css', array(), $this->version, 'all' );
		wp_enqueue_style( self::PREFIX . '2.chunk', WP_RENTSYST_PLUGIN_URL . '/resources/static/css/2.chunk.css', array(), $this->version, 'all' );
	}

	public function registerJS()
	{
		wp_enqueue_script( self::PREFIX . 'google_maps', 'https://maps.googleapis.com/maps/api/js?key=' . get_option(RS_Company::COMPANY_SETTINGS_ID)->maps_key . '&libraries=places', [], $this->version, true );
		wp_enqueue_script( self::PREFIX .'public', WP_RENTSYST_PLUGIN_URL . '/public/js/rentsyst-public.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( self::PREFIX .'fancybox', WP_RENTSYST_PLUGIN_URL . '/public/js/fancybox.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( self::PREFIX .'slick', WP_RENTSYST_PLUGIN_URL . '/public/js/slick.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( self::PREFIX .'catalog', WP_RENTSYST_PLUGIN_URL . '/public/js/catalog.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( self::PREFIX .'bundle_front', WP_RENTSYST_PLUGIN_URL . '/resources/static/js/bundle_front.js', array( 'jquery' ), $this->version, true );
	}

	public static function registerForPayment()
	{
		wp_enqueue_style( self::PREFIX . 'fico', WP_RENTSYST_PLUGIN_URL . '/resources-payments/css/fico.css', array(), RENTSYST_VERSION, 'all' );
		wp_enqueue_script( self::PREFIX . 'google_maps', 'https://maps.googleapis.com/maps/api/js?key=' . get_option(RS_Company::COMPANY_SETTINGS_ID)->maps_key . '&libraries=places', [], RENTSYST_VERSION, true );
		wp_enqueue_script( self::PREFIX .'bundle_payments', WP_RENTSYST_PLUGIN_URL . '/resources-payments/static/js/bundle_payment.js', array( 'jquery' ), RENTSYST_VERSION, true );

	}
}
