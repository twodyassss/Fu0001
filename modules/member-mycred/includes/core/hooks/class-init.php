<?php
namespace um_ext\um_mycred\core\hooks;


if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Init
 *
 * @package um_ext\um_mycred\core\hooks
 */
class Init {


	/**
	 * Init constructor.
	 */
	function __construct() {
		add_filter( 'mycred_setup_hooks', array( $this, 'register_custom_hooks' ), 10, 2 );
		add_filter( 'mycred_all_references', array( $this, 'references' ), 10, 1 );
	}


	/**
	 * Core Hooks
	 *
	 * @param array $installed
	 * @param $point_type
	 *
	 * @return array
	 */
	public function register_custom_hooks( $installed, $point_type ) {
		// Register
		$installed['um-user-register'] = array(
			'title'        => __( 'Ultimate Member - Registration', 'twodayssss' ),
			'description'  => __( 'Award points for register hooks', 'twodayssss' ),
			'callback'     => array( 'UM_myCRED_Register_Hooks' )
		);

		// Login
		$installed['um-user-login'] = array(
			'title'        => __( 'Ultimate Member - Login', 'twodayssss' ),
			'description'  => __( 'Award points for login hooks', 'twodayssss' ),
			'callback'     => array( 'UM_myCRED_Login_Hooks' )
		);

		// Profile
		$installed['um-user-profile'] = array(
			'title'        => __( 'Ultimate Member - Profile', 'twodayssss' ),
			'description'  => __( 'Award points for profile hooks', 'twodayssss' ),
			'callback'     => array( 'UM_myCRED_Profile_Hooks' )
		);

		// Account
		$installed['um-user-account'] = array(
			'title'        => __( 'Ultimate Member - Account', 'twodayssss' ),
			'description'  => __( 'Award points for account hooks', 'twodayssss' ),
			'callback'     => array( 'UM_myCRED_Account_Hooks' )
		);

		// Member Directory
		$installed['um-member-directory'] = array(
			'title'        => __( 'Ultimate Member - Member Directory', 'twodayssss' ),
			'description'  => __( 'Award points for Member Directory hooks', 'twodayssss' ),
			'callback'     => array( 'UM_myCRED_Member_Directory_Hooks' )
		);


		$installed = apply_filters( 'um_mycred_hooks_installed__filter', $installed );

		return $installed;
	}


	/**
	 * @param $hooks
	 *
	 * @return mixed
	 */
	public function references( $hooks ) {

		$hooks = array_merge( $hooks, array(
			'um-user-register'  => __( 'Ultimate Member - Completing registration', 'twodayssss' ),
			'um-user-login'     => __( 'Ultimate Member - Logging via UM Login Form', 'twodayssss' ),
			'profile_photo'     => __( 'Ultimate Member - Uploading Profile Photo', 'twodayssss' ),
			'cover_photo'       => __( 'Ultimate Member - Uploading Cover Photo', 'twodayssss' ),
			'update_profile'    => __( 'Ultimate Member - Updating Profile', 'twodayssss' ),
			'update_account'    => __( 'Ultimate Member - Updating Account', 'twodayssss' ),
			'member_search'     => __( 'Ultimate Member - Using Search in Members Directory', 'twodayssss' ),
		) );

		return $hooks;
	}

}