<?php

use rentsyst\admin\components\Rentsyst_CatalogVehicles;

require_once WP_RENTSYST_PLUGIN_DIR . '/admin/components/Rentsyst_CatalogVehicles.php';

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Rentsyst
 * @subpackage rentsyst/includes
 * @author     Your Name <email@example.com>
 */
class Rentsyst_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		self::createResponsePaymentPages();

		Rentsyst_CatalogVehicles::create();
		flush_rewrite_rules();
	}

	private static function createResponsePaymentPages()
	{
		if(!get_option('rentsyst_confirmation_page_id')) {
			$postarr = [
				'post_content' => '[rentsyst_payment]',
				'post_title'   => 'Confirmation page',
				'post_name'    => 'rentsyst_confirmation_page',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			];
			$id      = wp_insert_post( $postarr );
			update_option( 'rentsyst_confirmation_page_id', $id );
		}
	}

}
