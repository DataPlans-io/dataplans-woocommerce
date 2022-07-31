<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dataplans.io/
 * @since      1.0.0
 *
 * @package    Dataplans
 * @subpackage Dataplans/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dataplans
 * @subpackage Dataplans/admin
 * @author     DataPlans.io <hi@dataplans.io>
 */
class DPWC_Dataplans_Admin {

	/**
	 * @var array
	 */
	private $admin_options = array(
		'environment'          =>
			array(
				'label'    => 'Environment',
				'renderer' => 'render_environment',
			),
		'api_access_token'     =>
			array(
				'label'       => 'API Access Token',
				'description' => 'Generate API Access Token on DataPlans.io',
				'renderer'    => 'render_options_text',
			),
		'display_qrcode_in_email' =>
			array(
				'label'       => 'Display QR Code in Email',
				'description' => 'Display QR Code in Email when new order created',
				'renderer'    => 'render_displaying_inemail_qrcode_checkbox',
			),
		'balancelimit_alert' =>
			array(
				'label'       => 'Balance Alert Limit',
				'description' => 'Low balance notification. Email will send to admin when balance is below this amount',
				'renderer'    => 'render_balancelimit_alert_CBF',
			),
		// 'auto_complete_orders' =>
		// 	array(
		// 		'label'       => 'Auto-complete orders',
		// 		'description' => 'Get orders automatically completed upon payment. This option only works with IPN payments (eg. PayPal).',
		// 		'renderer'    => 'render_orders_checkbox',
		// 	)
	);

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
	 * @var      array
	 */
	private $plugin_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in DPWC_Dataplans_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The DPWC_Dataplans_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dataplans-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( "dpwc_datatable_id_css", plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in DPWC_Dataplans_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The DPWC_Dataplans_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( "dpwc_datatable_id_js", plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ));
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dataplans-admin.js', array( 'jquery' ), $this->version, false );
	}


	function init_addmetabox_select_api_product_planCBF(){		
		add_meta_box('id_select_api_product_plan_metabox','Select API Product',array( $this, 'select_api_product_plan_metaboxCBF'),'product','normal','high');	
		add_meta_box('id_api_purchased_product_details_metabox','API Purchased Product Details',array( $this, 'api_purchased_order_product_details_metaboxCBF'),'shop_order','normal','default');	
	}


	function select_api_product_plan_metaboxCBF($cur_postObj){
		$settings_arr = get_option("dpio_options");
		//echo '<pre>'; print_r($settings_arr); echo '</pre>';
		$selected_api_pplan = get_metadata('post',$cur_postObj->ID,'selected_api_product_plan',true);
		if(isset($settings_arr['api_access_token']) && trim($settings_arr['api_access_token']) != ''){
			if($settings_arr['environment'] == 1)
				$url = "https://app.dataplans.io/api/v1/plans";
			else
				$url = "https://sandbox.dataplans.io/api/v1/plans";

			$args = array(
				'headers'     => array(
					'Authorization' => $settings_arr['api_access_token']
				),
			); 
	
				$http = _wp_http_get_object();
		
				$result = $http->get( $url, $args );

				if(isset($result->errors))
				return;
				if(isset($result['response']['code']) && $result['response']['code'] != '200')
					return;

				$result = json_decode($result['body']);
	
				//echo '<pre>'.print_r(json_decode($result['body'])).'</pre>';


			$selected_api_product_plan_obj = '';
			if(isset($result[0])){?>
				<select name="selected_api_product_plan">
						<option value="no_selected_api_product_plan"><?php esc_html_e("None",'dataplans');?></option><?php
				
				foreach ($result as $api_prod_obj) {
					$selct_var = ($selected_api_pplan == $api_prod_obj->slug ? 'selected' : '');
					printf('<option value="%s" %s> %s </option>',$api_prod_obj->slug,$selct_var,$api_prod_obj->name.' - '.$api_prod_obj->retailPrice.' '.$api_prod_obj->priceCurrency);
				} // foreach ($result as $api_prod_obj)?>
				</select>

				<p><input type="submit" name="insertapi_infointo_desc" class="preview button" value="Insert Selected API Product info into description?" /></p><p>&nbsp;</p>
				<?php
				foreach ($result as $api_prod_obj){
					if($selected_api_pplan == $api_prod_obj->slug){
						$selected_api_product_plan_obj = base64_encode(serialize($api_prod_obj));

						printf(' <input type="hidden" name="selected_api_info" value="%s" />',$selected_api_product_plan_obj);
						break;
					}
				}

			} // if(isset($result[0]))

			wp_nonce_field('select_apiplan_nonce', 'dpwc-select_apiplan_nonce');
		} // if(isset($settings_arr['api_access_token']) 
		

	} // function


	function api_purchased_order_product_details_metaboxCBF($cur_postObj){
		$product_plan_purchase_arr = get_metadata('post',$cur_postObj->ID,'selected_api_product_plan_purchase_array',true);
		$settings_arr = get_option("dpio_options");
		if(isset($settings_arr['api_access_token']) && trim($settings_arr['api_access_token']) != ''){
			
			if(isset($product_plan_purchase_arr->purchase)){?>
				<table>
					<tr>
						<th><?php esc_html_e("WooCommerce Order ID",'dataplans')?></th>
						<td><?php esc_html_e($cur_postObj->ID)?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Purchase Date",'dataplans')?></th>
						<td><?php esc_html_e(date("Y-m-d H:i a",strtotime($product_plan_purchase_arr->purchase->purchasedAt)))?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Expiry Date",'dataplans')?></th>
						<td><?php esc_html_e(date("Y-m-d H:i a",strtotime($product_plan_purchase_arr->purchase->esim->expiryDate)))?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Plan Slug",'dataplans')?></th>
						<td><?php esc_html_e($product_plan_purchase_arr->purchase->planSlug)?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Retail Price",'dataplans')?></th>
						<td><?php esc_html_e($product_plan_purchase_arr->purchase->retail)?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Paid",'dataplans')?></th>
						<td><?php esc_html_e($product_plan_purchase_arr->purchase->paid)?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Currency",'dataplans')?></th>
						<td><?php esc_html_e($product_plan_purchase_arr->purchase->currency)?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Phone",'dataplans')?></th>
						<td><?php esc_html_e($product_plan_purchase_arr->purchase->esim->phone)?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("Serial",'dataplans')?></th>
						<td><?php esc_html_e($product_plan_purchase_arr->purchase->esim->serial)?></td>
					</tr>
					<tr>
						<th><?php esc_html_e("LPA Value",'dataplans')?></th>
						<td><?php esc_html_e($product_plan_purchase_arr->purchase->esim->qrCodeString) ?></td>
					</tr>
				</table>

		<?php
			} // if(isset($result['purchaseId'])){

		}

		
	}


	function woocommerce_before_order_itemmeta( ){

		$order_id =  sanitize_text_field($_REQUEST['post']);

		$product_plan_purchase_arr = get_metadata('post',$order_id,'selected_api_product_plan_purchase_array',true);
		if(isset($product_plan_purchase_arr->purchase->planName)) {?>
			<h3><?php esc_html_e("eSim Code",'dataplans')?></h3>
			<table height="100%" width="100%">
				<tr>
					<td><strong><?php esc_html_e("Product",'dataplans')?></strong></td>
					<td><strong><?php esc_html_e("ESIM CODE",'dataplans')?></strong></td>
				</tr>
				<tr>
					<td><?php esc_html_e($product_plan_purchase_arr->purchase->planName)?></td>
					<?php printf('<td><img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=%s&choe=UTF-8"><br /><span class="dashicons dashicons-phone"></span> %s</td>',$product_plan_purchase_arr->purchase->esim->qrCodeString,$product_plan_purchase_arr->purchase->esim->phone);?>
				</tr>
			</table>
			<?php
		}
	}


	function sendemail_customer_completed_order_apiCBF($emails){
		$emails['DPWC_WC_Email_Customer_Low_Balance_Notification_Api'] = include dataplans_PATH.'/admin/class-wc-email-customer-api-low-balance-notification.php';
		$emails['DPWC_WC_Email_Customer_Completed_Order_Api'] = include dataplans_PATH.'/admin/class-wc-email-customer-completed-order.php';
		return $emails;
	}

	function wc_email_after_order_table($order){
		$settings_arr = get_option("dpio_options");
		$order_data = $order->get_data();
		$order_id = $order_data['id'];
		$dplan_curbalance = get_option("current_balance_api_product_purchases");
		$product_plan_purchase_arr = get_metadata('post',$order_id,'selected_api_product_plan_purchase_array',true);
		?>
		<?php if(isset($settings_arr['display_qrcode_in_email']) && $product_plan_purchase_arr){
			$showstatus = (trim($product_plan_purchase_arr->purchase->esim->phone) != '' ? '<span class="dashicons dashicons-phone"></span>'.$product_plan_purchase_arr->purchase->esim->phone : '')?>
			<h3><?php esc_html_e("eSim Code",'dataplans')?></h3>
			<table height="100%" width="100%">
				<tr>
					<td><strong><?php esc_html_e("Product",'dataplans')?></strong></td>
					<td><strong><?php esc_html_e("ESIM CODE",'dataplans')?></strong></td>
				</tr>
				<tr>
				<?php printf('<td>%s</td>',$product_plan_purchase_arr->purchase->planName)?>
				<?php printf('<td><img src="%s"><br />%s</td>',$product_plan_purchase_arr->purchase->esim->qrCodeDataUrl,$showstatus)?>
				</tr>
			</table>
			<?php
		}

		
	}



	function run_DPWC_WC_Email_Customer_Completed_Order_Api_CBF(){
		if(isset($_GET['dataplan_action']) && isset($_GET['oid'])){
			$oid = sanitize_text_field($_GET['oid']);
			WC()->mailer()->emails['DPWC_WC_Email_Customer_Completed_Order_Api']->trigger($oid, wc_get_order($oid) );
			
			
			//wp_mail("customer1@wp1.com","MAM Subj","MAM MAS Messageeeeeeeeeeee");

			wp_safe_redirect(admin_url("admin.php?page=dpio-history"));
			exit;
		}
	}
	




	function removecustom_wc_email_settings_resend_lowbal_CBF(){
		?>
		<script>
			jQuery(document).ready(function(){
				jQuery('.wc-email-settings-table-name').find('a').each(function(index, value) {
					var resendemail_indexoff = (jQuery(value).attr('href').indexOf('DPWC_WC_Email_Customer_Completed_Order_Api'));
					var lowbalemail_indexoff = (jQuery(value).attr('href').indexOf('DPWC_WC_Email_Customer_Low_Balance_Notification_Api'));

					if(resendemail_indexoff > 0)
						jQuery(value).parent().parent().remove();

					if(lowbalemail_indexoff > 0)
						jQuery(value).parent().parent().remove();
						
				});
			});
  		</script>
	<?php
	}
	


	function save_select_api_product_planCBF($cur_post_id){
		global $wpdb;
		if(isset($_POST['selected_api_product_plan']) && wp_verify_nonce( $_POST['dpwc-select_apiplan_nonce'], 'select_apiplan_nonce' ))
			update_metadata('post',$cur_post_id,'selected_api_product_plan',sanitize_text_field($_POST['selected_api_product_plan']));


		if(isset($_POST['insertapi_infointo_desc']) && wp_verify_nonce( $_POST['dpwc-select_apiplan_nonce'], 'select_apiplan_nonce' )){

			$countries_coma_sep = '';
			$selected_api_info = unserialize(base64_decode(sanitize_text_field($_POST['selected_api_info'])));

			$countries_arr_obj = $selected_api_info->countries;
			
			foreach ($countries_arr_obj as $key => $value)
				$countries_coma_sep .= $value->countryName.', ';
				
			$this_postObj = get_post($cur_post_id);
			$cur_postcontent = $this_postObj->post_content;
			$updated_postcontent = $cur_postcontent.'<br /><br />'.'<strong>API Product Name</strong>: '.($selected_api_info->name).'<br /><strong>Capacity/ Unit</strong>: '.($selected_api_info->capacity).'/ '.($selected_api_info->capacityUnit).'<br /><strong>Region</strong>: '.($selected_api_info->region->name).'<br /><strong>Countries</strong>: '.$countries_coma_sep;

			$wpdb->update($wpdb->posts, array("post_content"=>$updated_postcontent), array('ID'=>$cur_post_id));

			//wp_update_post(array("ID"=>$cur_post_id,"post_content"=>$updated_postcontent));

		}

	}


	/**
	 * Adds a settings page link to a menu
	 *
	 * @link        https://codex.wordpress.org/Administration_Menus
	 * @since        1.0.0
	 * @return        void
	 */
	public function add_menu() {
		// TODO - Update page_title, menu_title to support multi languages
		add_menu_page(
			__( 'DataPlans.io', DataplansConst::I18N_NAME ),
			__( 'DataPlans.io', DataplansConst::I18N_NAME ),
			'activate_plugins',
			'dataplans',
			array( $this, 'display_settings_page' ),
			plugin_dir_url( __FILE__ ) . 'img/menu_icon.png',
			'58.1'
		);

		add_submenu_page(
			'dataplans',
			__( 'DataPlans.io Settings', DataplansConst::I18N_NAME ),
			__( 'Settings', DataplansConst::I18N_NAME ),
			'activate_plugins',
			'dataplans'
		);

		add_submenu_page(
			'dataplans',
			__( 'DataPlans.io Order History', DataplansConst::I18N_NAME ),
			__( 'Order History', DataplansConst::I18N_NAME ),
			'activate_plugins',
			'dpio-history',
			array( $this, 'display_history_page' )
		);

		// add_submenu_page(
		// 	'dataplans',
		// 	__( 'DataPlans.io Emails Template', DataplansConst::I18N_NAME ),
		// 	__( 'Emails Template', DataplansConst::I18N_NAME ),
		// 	'activate_plugins',
		// 	'dpio-email',
		// 	array( $this, 'display_email_page' )
		// );
	}

	/**
	 *
	 */
	public function section_one_callback() {
		// section one description
	}

	/**
	 * Return options for CW plugin
	 */
	public function get_options() {
		if ( empty( $this->plugin_options ) || count( $this->plugin_options ) == 0 ) {
			$this->plugin_options = get_option( DataplansConst::OPTIONS_NAME );
		}

		return $this->plugin_options;
	}

	public function update_options( $option ) {
	}

	/**
	 * @param string $version
	 */
	public function register_settings() {
		register_setting( 'dpio-settings-group', DataplansConst::OPTIONS_NAME );
		add_settings_section( 'dpio-settings-section', '', array(
			$this,
			'section_one_callback'
		), 'dpio_options_page_slug' );

		add_action( 'update_option_' . DataplansConst::OPTIONS_NAME, array( $this, 'update_options' ) );

		$options = $this->get_options();
		foreach ( $this->admin_options as $option_key => $option ) {
			add_settings_field( $option_key, $option['label'], array(
				$this,
				$option['renderer']
			), 'dpio_options_page_slug', 'dpio-settings-section',
				array(
					'name'    => $option_key,
					'options' => $options,
					'class'   => $option['class']
				) );
		}
	}

	private function display_dataplans_header() {
		$_options = $this->get_options();
		$_mode = intval($_options['environment']);
		$_token = $_options['api_access_token'];

		// Define data
		define('DATAPLANS_TOKEN', $_token);
		define('DATAPLANS_API_MODE', $_mode === 1 ? 'app' : 'sandbox');
		define('DATAPLANS_API_VERSION', 1);

		// Create info variables
        $mode = null;
        $status =  null;
        $balance = null;

		// Get balance API ​
        try {
			$url = "https://".DATAPLANS_API_MODE.".dataplans.io/api/v1/accountBalance";

			$args = array(
				'headers'     => array(
					'Authorization' => DATAPLANS_TOKEN
				),
			); 
	
				$http = _wp_http_get_object();
		
				$result = $http->get( $url, $args );

				if(isset($result->errors))
				return;

				
				if(isset($result['response']['code']) && $result['response']['code'] != '200')
					return;

				$result = json_decode($result['body']);


				
			if(isset($result->availableBalance)){
	       		$status = 'online';
				$balance = $result->availableBalance;
			}
			else
				$status = 'offline';
				
			
        } catch (Exception $error) {
	        $status = 'offline';
        } finally {
	        $mode = ($_mode === 1) ? 'live' : 'sandbox';
        }

	    // Render header
		require( 'partials/' . $this->plugin_name . '-admin-header.php' );
	}

	/**
	 *
	 */
	public function display_history_page() {
		$this->display_dataplans_header();
		require_once 'partials/' . $this->plugin_name . '-admin-history-display.php';
	}

	/**
	 *
	 */
	public function display_email_page() {
		$this->display_dataplans_header();
	}

	/**
	 *
	 */
	public function display_settings_page() {
		$this->display_dataplans_header();
		require_once 'partials/' . $this->plugin_name . '-admin-settings-display.php';
	}

	/**
	 * @param array $args
	 */
	public function render_environment( $args = array() ) {
		?>
        <label class="mr-2" title="Sandbox">
            <input type="radio" name="dpio_options[<?php echo $args['name'] ?>]"
                   value="0" <?php if ( $args['options'][ $args['name'] ] == 0 ) { ?> checked <?php } ?>>
            <span class="input-desc">Sandbox</span>
        </label>
        <label title="Live">
            <input type="radio" name="dpio_options[<?php echo $args['name'] ?>]" value="1"
				<?php if ( $args['options'][ $args['name'] ] == 1 ) { ?> checked <?php } ?>>
            <span class="input-desc">Live</span>
        </label>
		<?php
	}

	/**
	 * @param array $args
	 */
	public function render_options_text( $args = array() ) {
		printf(
			'<input type="password" id="%s" name="dpio_options[%s]" value="%s" pattern="[^\s]+" title="White spaces are not allowed, please double check the values." /><p class="description cst-desc">%s</p>',
			$args['name'],
			$args['name'],
			$args['options'][ $args['name'] ],
			$this->admin_options[ $args['name'] ]['description']
		);
	}


	function render_displaying_inemail_qrcode_checkbox( $args = array() ) {
		$settings_arr = get_option("dpio_options");
		//echo '<pre>';print_r($settings_arr);echo '</pre>';
		printf(
			'<input type="checkbox" id="%s" name="dpio_options[%s]" value="1" %s /><p class="description cst-desc">%s</p>',
			$args['name'],
			$args['name'],
			( isset( $args['options'][ $args['name'] ] ) && $args['options'][ $args['name'] ] ) == 1 ? "checked" : "",
			$this->admin_options[ $args['name'] ]['description']
		);
	}


	function render_balancelimit_alert_CBF( $args = array() ) {
		$settings_arr = get_option("dpio_options");
		//echo '<pre>';print_r($settings_arr);echo '</pre>';
		printf(
			'<input type="text" id="%s" name="dpio_options[%s]" value="%s" pattern="[^\s]+" /><p class="description cst-desc">%s</p>',
			$args['name'],
			$args['name'],
			$args['options'][ $args['name'] ],
			$this->admin_options[ $args['name'] ]['description']
		);
	}

	/**
	 * @param array $args
	 */
	public function render_orders_checkbox( $args = array() ) {
		printf(
			'<input type="checkbox" id="%s" name="dpio_options[%s]" value="1" %s /><p class="description cst-desc">%s</p>',
			$args['name'],
			$args['name'],
			( isset( $args['options'][ $args['name'] ] ) && $args['options'][ $args['name'] ] ) == 1 ? "checked" : "",
			$this->admin_options[ $args['name'] ]['description']
		);
	}

}
