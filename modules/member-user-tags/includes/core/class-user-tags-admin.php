<?php
namespace um_ext\um_user_tags\core;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class User_Tags_Admin
 * @package um_ext\um_user_tags\core
 */
class User_Tags_Admin {


	/**
	 * User_Tags_Admin constructor.
	 */
	function __construct() {
		$this->pagehook = 'toplevel_page_ultimatemember';
		add_action( 'um_extend_admin_menu',  array( &$this, 'um_extend_admin_menu' ), 5 );
	}


	/**
	 * Add User Tags submenu
	 */
	function um_extend_admin_menu() {
		add_submenu_page( 'ultimatemember', __( '会员标签', 'twodayssss' ), __( '会员标签', 'twodayssss' ), 'manage_options', 'edit-tags.php?taxonomy=um_user_tag', '' );
	}

}