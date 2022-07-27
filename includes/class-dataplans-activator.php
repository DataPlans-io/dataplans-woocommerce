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
class DPWC_Dataplans_Activator {

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
		DPWC_Dataplans_Activator::update_balance();
	}

	public static function update_balance(){	
		/* Update Balance */
		
		$settings_arr = get_option("dpio_options");
		if($settings_arr['environment'] == 1)
			$url = "https://app.dataplans.io/api/v1/accountBalance";
		else
			$url = "https://sandbox.dataplans.io/api/v1/accountBalance";
		
			$args = array(
				'headers'     => array(
					'Authorization' => $settings_arr['api_access_token']
				),
			); 
	
				$http = _wp_http_get_object();
		
				$result = $http->get( $url, $args );

				$result = json_decode($result['body']);

		if(isset($result->availableBalance)){
			update_option('current_balance_api_product_purchases',$result->availableBalance);
		}
	}

}
