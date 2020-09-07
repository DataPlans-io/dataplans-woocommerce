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
class Dataplans_Admin {

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
		'auto_complete_orders' =>
			array(
				'label'       => 'Auto-complete orders',
				'description' => 'Get orders automatically completed upon payment. This option only works with IPN payments (eg. PayPal).',
				'renderer'    => 'render_orders_checkbox',
			),
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
		 * defined in Dataplans_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dataplans_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dataplans-admin.css', array(), $this->version, 'all' );

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
		 * defined in Dataplans_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dataplans_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dataplans-admin.js', array( 'jquery' ), $this->version, false );

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

		add_submenu_page(
			'dataplans',
			__( 'DataPlans.io Emails Template', DataplansConst::I18N_NAME ),
			__( 'Emails Template', DataplansConst::I18N_NAME ),
			'activate_plugins',
			'dpio-email',
			array( $this, 'display_email_page' )
		);
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

		// Get balance API
        try {
	        // Get cache
	        $balance = wp_cache_get('balance', DataplansConst::NAME);

	        // Check cache
	        if (empty($balance)) {
		        $response = DataPlansBalance::retrieve();
		        $balance = number_format(floatval($response['availableBalance']), 2) . ' THB';

		        // Set cache
                wp_cache_set('balance', $balance, DataplansConst::NAME, 3);
	        }

	        $status = 'online';
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
			'<input type="text" id="%s" name="dpio_options[%s]" value="%s" pattern="[^\s]+" title="White spaces are not allowed, please double check the values." /><p class="description cst-desc">%s</p>',
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
