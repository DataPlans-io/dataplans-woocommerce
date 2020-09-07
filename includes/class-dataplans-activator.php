<?php

/**
 * Fired during plugin activation
 *
 * @link       https://dataplans.io/
 * @since      1.0.0
 *
 * @package    Dataplans
 * @subpackage Dataplans/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Dataplans
 * @subpackage Dataplans/includes
 * @author     DataPlans.io <hi@dataplans.io>
 */
class Dataplans_Activator {

	public static $default = array(
		'environment'          => 0,
		'api_access_token'     => '',
		'auto_complete_orders' => 0,
	);

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_option( DataplansConst::OPTIONS_NAME, self::$default );
	}

}
