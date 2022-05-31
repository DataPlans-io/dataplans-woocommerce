<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dataplans.io/
 * @since      1.0.0
 *
 * @package    Dataplans
 * @subpackage Dataplans/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dataplans
 * @subpackage Dataplans/includes
 * @author     DataPlans.io <hi@dataplans.io>
 */
class Dataplans {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dataplans_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'DATAPLANS_VERSION' ) ) {
			$this->version = DATAPLANS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'dataplans';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dataplans_Loader. Orchestrates the hooks of the plugin.
	 * - Dataplans_i18n. Defines internationalization functionality.
	 * - Dataplans_Admin. Defines all hooks for the admin area.
	 * - Dataplans_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dataplans-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dataplans-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dataplans-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dataplans-public.php';

		$this->loader = new Dataplans_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dataplans_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dataplans_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dataplans_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'init_addmetabox_select_api_product_planCBF' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_select_api_product_planCBF' );		
		$this->loader->add_action( 'woocommerce_email_after_order_table', $plugin_admin, 'wc_email_after_order_table' );
		
		
		$this->loader->add_filter('woocommerce_email_classes', $plugin_admin, 'sendemail_customer_completed_order_apiCBF' );
		$this->loader->add_action('init',$plugin_admin,'run_WC_Email_Customer_Completed_Order_Api_CBF');
		$this->loader->add_action( 'woocommerce_before_order_itemmeta', $plugin_admin,'woocommerce_before_order_itemmeta');
		$this->loader->add_action( 'admin_notices', $plugin_admin,'removecustom_wc_email_settings_resend_lowbal_CBF');




		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu', 11 );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
//        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_sections' );
//        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_fields' );
//        $this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notices' );
//        $this->loader->add_action( 'admin_init', $plugin_admin, 'admin_notices_init' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dataplans_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_public, 'wc_order_status_completed' );
		
		$this->loader->add_action( 'woocommerce_view_order', $plugin_public, 'woocommerce_view_order'  );
		//$this->loader->add_filter( 'wp_mail_content_type', $plugin_public, 'dataplan_wp_mail_content_typeCBF');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
		
		$settings_arr = get_option("dpio_options");
		if(!empty($settings_arr['api_access_token']) && !get_option('current_balance_api_product_purchases')) 
		Dataplans::update_balance();
	}

	public static function update_balance(){	
		/* Update Balance */
		
		$settings_arr = get_option("dpio_options");
		if($settings_arr['environment'] == 1)
			$url = "https://app.dataplans.io/api/v1/accountBalance";
		else
			$url = "https://sandbox.dataplans.io/api/v1/accountBalance";
		
		 $curl = curl_init($url);
		 curl_setopt($curl, CURLOPT_URL, $url);
		 $headers = [
				'accept: application/json',				
				'Authorization: '.$settings_arr['api_access_token']
			];
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($curl);
		$result = json_decode($result);
		if(isset($result->availableBalance)){
			update_option('current_balance_api_product_purchases',$result->availableBalance);
		}
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dataplans_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

final class DataplansConst
{
	const NAME = 'DataPlans.io';
	const I18N_NAME = 'dataplans';
	const OPTIONS_NAME = 'dpio_options';
}
