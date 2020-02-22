<?php
/*
Module Name: Member - Social Login
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'um_social_login_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_social_login_path', trailingslashit( __DIR__ ) );
define( 'um_social_login_version', '2.10.7' );


require_once um_social_login_path . 'includes/core/member-social-login-init.php';

	//first install
	$version = get_option( 'um_social_login_version' );
	if ( ! $version ) {
		update_option( 'um_social_login_last_version_upgrade', um_social_login_version );
	}

	if ( $version != um_social_login_version ) {
		update_option( 'um_social_login_version', um_social_login_version );
	}

	//run setup
	if ( ! class_exists( 'um_ext\um_social_login\core\Social_Login_Setup' ) ) 
		require_once um_social_login_path . 'includes/core/class-social-login-setup.php';

	$social_login_setup = new um_ext\um_social_login\core\Social_Login_Setup();
	$social_login_setup->run_setup();
