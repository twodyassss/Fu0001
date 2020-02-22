<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class UM_WooCommerce_API
 */
class UM_WooCommerce_API {


	/**
	 * @var
	 */
	private static $instance;


	/**
	 * @return UM_WooCommerce_API
	 */
	static public function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * UM_WooCommerce_API constructor.
	 */
	function __construct() {
		// Global for backwards compatibility.
		$GLOBALS['um_woocommerce'] = $this;
		add_filter( 'um_call_object_WooCommerce_API', array( &$this, 'get_this' ) );

		$this->access();
		$this->account();

		if ( UM()->is_request( 'admin' ) ) {
			$this->admin();
			$this->admin_upgrade();
		}
		add_action( 'init', array(&$this, 'init'), 0 );
		add_action( 'wp_enqueue_scripts',  array( &$this, 'wp_enqueue_scripts' ), 9999 );
		add_filter( 'um_settings_default_values', array( &$this, 'default_settings' ), 10, 1 );
		add_action( 'wp_ajax_um_woocommerce_get_order', array( $this->api(), 'ajax_get_order' ) );
		add_action( 'wp_ajax_um_woocommerce_get_subscription', array( $this->api(), 'ajax_get_subscription' ) );
	}


	/**
	 * Frontend scripts
	 */
	function wp_enqueue_scripts() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG || defined( 'UM_SCRIPT_DEBUG' ) ) ? '' : '.min';

		wp_register_script( 'twodayssss', um_woocommerce_url . 'assets/js/um-woocommerce' . $suffix . '.js', array( 'jquery', 'select2', 'wp-util', 'um_raty', 'um_scripts' ), um_woocommerce_version, true );
		wp_register_style( 'twodayssss', um_woocommerce_url . 'assets/css/um-woocommerce' . $suffix . '.css', array( 'um_raty' ), um_woocommerce_version );
	}


	/**
	 * @param $defaults
	 *
	 * @return array
	 */
	function default_settings( $defaults ) {
		$defaults = array_merge( $defaults, $this->setup()->settings_defaults );
		return $defaults;
	}


	/**
	 * @return um_ext\um_woocommerce\core\WooCommerce_Setup()
	 */
	function setup() {
		require_once um_woocommerce_path . 'includes/core/class-woocommerce-setup.php';
		if ( empty( UM()->classes['um_woocommerce_setup'] ) ) {
			UM()->classes['um_woocommerce_setup'] = new um_ext\um_woocommerce\core\WooCommerce_Setup();
		}
		return UM()->classes['um_woocommerce_setup'];
	}


	/**
	 * @return $this
	 */
	function get_this() {
		return $this;
	}


	/**
	 * @return um_ext\um_woocommerce\core\WooCommerce_Main_API()
	 */
	function api() {
		require_once um_woocommerce_path . 'includes/core/class-woocommerce-main-api.php';
		if ( empty( UM()->classes['um_woocommerce_api'] ) ) {
			UM()->classes['um_woocommerce_api'] = new um_ext\um_woocommerce\core\WooCommerce_Main_API();
		}
		return UM()->classes['um_woocommerce_api'];
	}


	/**
	 * @return um_ext\um_woocommerce\core\WooCommerce_Access()
	 */
	function access() {
		require_once um_woocommerce_path . 'includes/core/class-woocommerce-access.php';
		if ( empty( UM()->classes['um_woocommerce_access'] ) ) {
			UM()->classes['um_woocommerce_access'] = new um_ext\um_woocommerce\core\WooCommerce_Access();
		}
		return UM()->classes['um_woocommerce_access'];
	}


	/**
	 * @return um_ext\um_woocommerce\admin\core\Admin()
	 */
	function admin() {
		require_once um_woocommerce_path . 'includes/admin/core/class-admin.php';
		if ( empty( UM()->classes['um_woocommerce_admin'] ) ) {
			UM()->classes['um_woocommerce_admin'] = new um_ext\um_woocommerce\admin\core\Admin();
		}
		return UM()->classes['um_woocommerce_admin'];
	}

	/**
	 * @return um_ext\um_woocommerce\admin\core\Admin_Upgrade()
	 */
	function admin_upgrade() {
		require_once um_woocommerce_path . 'includes/admin/core/class-admin-upgrade.php';
		if ( empty( UM()->classes['um_woocommerce_admin_upgrade'] ) ) {
			UM()->classes['um_woocommerce_admin_upgrade'] = new um_ext\um_woocommerce\admin\core\Admin_Upgrade();
		}
		return UM()->classes['um_woocommerce_admin_upgrade'];
	}


	/**
	 * @return um_ext\um_woocommerce\core\WooCommerce_Account()
	 */
	function account() {
		require_once um_woocommerce_path . 'includes/core/class-woocommerce-account.php';
		if ( empty( UM()->classes['um_woocommerce_account'] ) ) {
			UM()->classes['um_woocommerce_account'] = new um_ext\um_woocommerce\core\WooCommerce_Account();
		}
		return UM()->classes['um_woocommerce_account'];
	}


	/**
	 * Init
	 */
	function init() {

		// Actions
		require_once um_woocommerce_path . 'includes/core/actions/um-woocommerce-tabs.php';
		require_once um_woocommerce_path . 'includes/core/actions/um-woocommerce-order.php';
		
		// Filters
		require_once um_woocommerce_path . 'includes/core/filters/um-woocommerce-fields.php';
		require_once um_woocommerce_path . 'includes/core/filters/um-woocommerce-reviews.php';
		require_once um_woocommerce_path . 'includes/core/filters/um-woocommerce-tabs.php';

	}
}

//create class var
if ( function_exists( 'UM' ) ) {
	UM()->set_class( 'WooCommerce_API', true );
}
