<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Rentsyst
 * @subpackage rentsyst/includes
 * @author     Your Name <email@example.com>
 */
class Rentsyst_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if(get_option('rentsyst_confirmation_page_id')) {
			wp_delete_post( get_option( 'rentsyst_confirmation_page_id' ) );
			delete_option( 'rentsyst_confirmation_page_id' );
		}
	}

}
