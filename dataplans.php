<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dataplans.io/
 * @since             1.0.0
 * @package           Dataplans
 *
 * @wordpress-plugin
 * Plugin Name:       DataPlans eSIMs for WooCommerce
 * Plugin URI:        https://esims.gitbook.io/dataplans/plugins
 * Description:       Sell eSIMs for digital delivery with WooCommerce and DataPlans.io
 * Version:           1.0.5
 * Author:            DataPlans
 * Author URI:        https://dataplans.io/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dataplans
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Autoload vendor
 */
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

register_activation_hook( __FILE__, 'dpwc_activate_dataplans' );
register_deactivation_hook( __FILE__, 'dpwc_deactivate_dataplans' );


/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DATAPLANS_VERSION', '1.0.3' );
define('dataplans_PATH', dirname(__FILE__));
define('dataplans_URL', plugin_dir_url(__FILE__));
define("DPWC_PDN", plugin_basename(dirname(__FILE__)));



/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dataplans-activator.php
 */
function dpwc_activate_dataplans() {
	require_once dataplans_PATH . '/includes/class-dataplans-activator.php';
	DPWC_Dataplans_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dataplans-deactivator.php
 */
function dpwc_deactivate_dataplans() {
	require_once dataplans_PATH . '/includes/class-dataplans-deactivator.php';
	DPWC_Dataplans_Deactivator::deactivate();
}



/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require dataplans_PATH . '/includes/class-dataplans.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function dpwc_run_dataplans() {

	$plugin = new DPWC_Dataplans();
	$plugin->run();

}

dpwc_run_dataplans();
