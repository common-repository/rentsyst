<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rentsyst.com/
 * @since             1.0.0
 * @package           Rentsyst
 *
 * @wordpress-plugin
 * Plugin Name:       RentSyst - CRM for a car rental business
 * Plugin URI:        https://rentsyst.com/wp/
 * Description:       Fleet management software for car rental business.
 * Version:           2.0.56
 * Author:            Rentsyst
 * Author URI:        https://rentsyst.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rentsyst
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
use rentsyst\includes\Rentsyst;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

define( 'RENTSYST_VERSION', '2.0.56' );
define( 'RENTSYST_PLUGIN_NAME', 'rentsyst' );
define( 'RENTSYST_MOD', 'prod');

define( 'WP_RENTSYST_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'WP_RENTSYST_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rentsyst-activator.php
 */
function activate_rentsyst() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rentsyst-activator.php';
	Rentsyst_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rentsyst-deactivator.php
 */
function deactivate_rentsyst() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rentsyst-deactivator.php';
	Rentsyst_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rentsyst' );
register_deactivation_hook( __FILE__, 'deactivate_rentsyst' );

function rentsyst_update_plugin( $upgrader_object, $options ) {
	$our_plugin = plugin_basename( __FILE__ );
	if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
		foreach( $options['plugins'] as $plugin ) {
			if( $plugin == $our_plugin ) {
				require_once plugin_dir_path( __FILE__ ) . 'includes/class-rentsyst-updater.php';
				Rentsyst_Updater::update();
			}
		}
	}
}
add_action( 'upgrader_process_complete', 'rentsyst_update_plugin', 10, 2 );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

require_once 'vendor/autoload.php';

$app = new Rentsyst();

$app->run();
