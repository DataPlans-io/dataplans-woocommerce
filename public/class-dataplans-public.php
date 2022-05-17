<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dataplans.io/
 * @since      1.0.0
 *
 * @package    Dataplans
 * @subpackage Dataplans/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Dataplans
 * @subpackage Dataplans/public
 * @author     DataPlans.io <hi@dataplans.io>
 */
class Dataplans_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dataplans_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dataplans_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dataplans-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dataplans_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dataplans_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dataplans-public.js', array( 'jquery' ), $this->version, false );

	}


	function save_inorder_api_prod_purchase_dataCBF($order_id){
		$settings_arr = get_option("dpio_options");
		$dplan_curbalance = get_option("current_balance_api_product_purchases");
		$flag_selected_api_product_plan = get_metadata('post',$order_id,'flag_selected_api_product_plan_purchase_array_inserted',true);
		if(isset($settings_arr['balancelimit_alert']) && trim($settings_arr['balancelimit_alert']) != '' && $dplan_curbalance <= $settings_arr['balancelimit_alert']){
			WC()->mailer()->emails['WC_Email_Customer_Low_Balance_Notification_Api']->trigger( $order_id, wc_get_order($order_id) );
			return;
		}

		if(strlen($flag_selected_api_product_plan) > 2)
			return;

        $order = wc_get_order( $order_id );
        $items = $order->get_items();

        foreach ( $items as $product )
           $pid = $product['product_id'];

		$settings_arr = get_option("dpio_options");
		$selected_api_pplan = get_metadata('post',$pid,'selected_api_product_plan',true);
		if(isset($settings_arr['api_access_token']) && trim($selected_api_pplan) != '' && trim($settings_arr['api_access_token']) != ''){
			if($settings_arr['environment'] == 1)
				$url = "https://app.dataplans.io/api/v1/purchases";
			else
				$url = "https://sandbox.dataplans.io/api/v1/purchases";
				
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);

			$headers = [
				'accept: application/json',				
				'Authorization: '.$settings_arr['api_access_token']
			];
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

						
			$postRequest = array(
				'slug' => $selected_api_pplan,
			);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $postRequest);

			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$result = curl_exec($curl);
			$result = json_decode($result);
			//update_option("mam_test_api_resutl",print_r($result,true));
			curl_close($curl);
			if(isset($result->purchase)){
				update_metadata('post',$order_id,'selected_api_product_plan_purchase_id',$result->purchase->purchaseId);
				update_metadata('post',$order_id,'selected_api_product_plan_purchase_qrcode',$result->purchase->esim->qrCodeString);
				update_metadata('post',$order_id,'selected_api_product_plan_purchase_array',$result);
				update_metadata('post',$order_id,'flag_selected_api_product_plan_purchase_array_inserted',"flag_selected_api_product_plan_purchase_array_inserted");
				update_option("current_balance_api_product_purchases",$result->availableBalance);
			}


			
		} // if(isset($settings_arr['api_access_token'])
	}// function


}
