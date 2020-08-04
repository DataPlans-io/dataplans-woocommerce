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
 * Plugin Name:       DataPlans.io
 * Plugin URI:        https://dataplans.io/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            DataPlans.io
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
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DATAPLANS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dataplans-activator.php
 */
function activate_dataplans() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dataplans-activator.php';
	Dataplans_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dataplans-deactivator.php
 */
function deactivate_dataplans() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dataplans-deactivator.php';
	Dataplans_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dataplans' );
register_deactivation_hook( __FILE__, 'deactivate_dataplans' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dataplans.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dataplans() {

	$plugin = new Dataplans();
	$plugin->run();

}
run_dataplans();
