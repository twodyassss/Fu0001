<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class UM_Groups
 */
class UM_Groups {


	/**
	 * @var
	 */
	private static $instance;


	/**
	 * @return UM_Groups
	 */
	static public function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * UM_Groups constructor.
	 */
	function __construct() {

		// Global for backwards compatibility.
		$GLOBALS['um_groups'] = $this;
		add_filter( 'um_call_object_Groups', array( &$this, 'get_this' ) );

		$this->api();
		$this->shortcode();
		$this->enqueue();
		$this->taxonomies();
		$this->form_process();
		$this->member();
		$this->discussion();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->ajax();
		}

		add_action('init', array(&$this, 'init'), 1);

		require_once um_groups_path . 'includes/um-groups-short-functions.php';
		require_once um_groups_path . 'includes/core/um-groups-widget.php';
		add_action( 'widgets_init', array(&$this, 'widgets_init' ) );

		add_filter( 'um_settings_default_values', array( &$this, 'default_settings' ), 10, 1 );

		add_action( 'init', array( &$this, 'create_post_type' ), 2 );

		add_filter( 'um_email_templates_path_by_slug', array( &$this, 'email_templates_path_by_slug' ), 10, 1 );
        
		add_filter('query_vars', array( &$this, 'query_vars' ), 10, 1 );
	}


	/**
	 * Set query vars
	 * @param  array $vars
	 * @return array
	 */
	function query_vars( $vars ) {
		$vars[ ] = "cat";
		$vars[ ] = "tags";
		$vars[ ] = "filter";
		$vars[ ] = "groups_search";

		return $vars;
	}


	/**
	 * @param $slugs
	 *
	 * @return mixed
	 */
	function email_templates_path_by_slug( $slugs ) {
		$slugs['groups_approve_member'] = um_groups_path . 'templates/email/';
		$slugs['groups_join_request'] = um_groups_path . 'templates/email/';
		$slugs['groups_invite_member'] = um_groups_path . 'templates/email/';
		$slugs['groups_new_post'] = um_groups_path . 'templates/email/';
		$slugs['groups_new_comment'] = um_groups_path . 'templates/email/';

		return $slugs;
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
	 *
	 */
	function create_post_type() {

		register_post_type( 'um_groups_discussion', array(
				'labels' => array(
					'name' => __( 'Groups Discussion' ),
					'singular_name' => __( 'Groups Discussion' ),
					'add_new' => __( 'Add New Post' ),
					'add_new_item' => __('Add New Post' ),
					'edit_item' => __('Edit Post'),
					'not_found' => __('No wall posts have been added yet'),
					'not_found_in_trash' => __('Nothing found in Trash'),
					'search_items' => __('Search Posts')
				),
				'public' => false,
				'supports' => array('editor'),
				'taxonomies' => array('um_hashtag'),
				'show_ui' => true,
				'show_in_menu' => false,
				'capability_type' => 'page'
			)
		);
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Form()
	 */
	function form_process() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Form' ) )
			require_once um_groups_path . 'includes/core/class-groups-form.php';
		
		if ( empty( UM()->classes['um_groups_form'] ) ) {
			UM()->classes['um_groups_form'] = new um_ext\um_groups\core\Groups_Form();
		}
		return UM()->classes['um_groups_form'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Setup()
	 */
	function setup() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Setup' ) )
			require_once um_groups_path . 'includes/core/class-groups-setup.php';

		if ( empty( UM()->classes['um_groups_setup'] ) ) {
			UM()->classes['um_groups_setup'] = new um_ext\um_groups\core\Groups_Setup();
		}
		return UM()->classes['um_groups_setup'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Main_API()
	 */
	function api() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Main_API' ) )
			require_once um_groups_path . 'includes/core/class-groups-main-api.php';
	
		if ( empty( UM()->classes['um_groups_main_api'] ) ) {
			UM()->classes['um_groups_main_api'] = new um_ext\um_groups\core\Groups_Main_API();
		}
		return UM()->classes['um_groups_main_api'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Ajax()
	 */
	function ajax() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Ajax' ) )
			require_once um_groups_path . 'includes/core/class-groups-ajax.php';

		if ( empty( UM()->classes['um_groups_ajax'] ) ) {
			UM()->classes['um_groups_ajax'] = new um_ext\um_groups\core\Groups_Ajax();
		}
		return UM()->classes['um_groups_ajax'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Shortcode()
	 */
	function shortcode() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Shortcode' ) )
			require_once um_groups_path . 'includes/core/class-groups-shortcode.php';

		if ( empty( UM()->classes['um_groups_shortcode'] ) ) {
			UM()->classes['um_groups_shortcode'] = new um_ext\um_groups\core\Groups_Shortcode();
		}
		return UM()->classes['um_groups_shortcode'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Enqueue()
	 */
	function enqueue() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Enqueue' ) )
			require_once um_groups_path . 'includes/core/class-groups-enqueue.php';

		if ( empty( UM()->classes['um_groups_enqueue'] ) ) {
			UM()->classes['um_groups_enqueue'] = new um_ext\um_groups\core\Groups_Enqueue();
		}
		return UM()->classes['um_groups_enqueue'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Taxonomies()
	 */
	function taxonomies() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Taxonomies' ) )
			require_once um_groups_path . 'includes/core/class-groups-taxonomies.php';

		if ( empty( UM()->classes['um_groups_taxonomies'] ) ) {
			UM()->classes['um_groups_taxonomies'] = new um_ext\um_groups\core\Groups_Taxonomies();
		}
		return UM()->classes['um_groups_taxonomies'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Member()
	 */
	function member() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Member' ) )
			require_once um_groups_path . 'includes/core/class-groups-member.php';

		if ( empty( UM()->classes['um_groups_member'] ) ) {
			UM()->classes['um_groups_member'] = new um_ext\um_groups\core\Groups_Member();
		}
		return UM()->classes['um_groups_member'];
	}


	/**
	 * @return um_ext\um_groups\core\Groups_Discussion()
	 */
	function discussion() {
		if ( ! class_exists( 'um_ext\um_groups\core\Groups_Discussion' ) )
			require_once um_groups_path . 'includes/core/class-groups-discussion.php';

		if ( empty( UM()->classes['um_groups_discussion'] ) ) {
			UM()->classes['um_groups_discussion'] = new um_ext\um_groups\core\Groups_Discussion();
		}
		return UM()->classes['um_groups_discussion'];
	}


	/**
	 * @return $this
	 */
	function get_this() {
		return $this;
	}


	/**
	 * Init
	 */
	function init() {

		// Actions
		require_once um_groups_path . 'includes/core/actions/um-groups-admin.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-actions.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-directory.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-form.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-single.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-discussion.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-email.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-profile.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-users-list.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-integrate-activity.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-integrate-notifications.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-integrate-followers.php';
		require_once um_groups_path . 'includes/core/actions/um-groups-integrate-friends.php';

		// Filters
		require_once um_groups_path . 'includes/core/filters/um-groups-users-list.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-settings.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-admin.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-single.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-directory.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-email.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-profile.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-media-library.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-integrate-activity.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-integrate-notifications.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-integrate-followers.php';
		require_once um_groups_path . 'includes/core/filters/um-groups-integrate-friends.php';
	}


	/**
	 *
	 */
	function widgets_init() {
		register_widget( 'UM_Groups_Own_Group' );
	}
}

//create class var
if ( function_exists( 'UM' ) ) {
	UM()->set_class( 'Groups', true );
}
