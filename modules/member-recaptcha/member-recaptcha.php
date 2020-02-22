<?php
/*
Module Name: Member - reCAPTCHA
*/

if ( ! defined( 'ABSPATH' ) ) exit;
define( 'um_recaptcha_url',trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_recaptcha_path', trailingslashit( __DIR__ ) );
define( 'um_recaptcha_plugin', 'member-recaptcha/member-recaptcha.php' );
define( 'um_recaptcha_version', '2.1.3' );

require_once um_recaptcha_path . 'includes/core/member-recaptcha-init.php';

	//first install
	$version = get_option( 'um_recaptcha_version' );
	if ( ! $version ) {
		update_option( 'um_recaptcha_last_version_upgrade', um_recaptcha_version );
	}

	if ( $version != um_recaptcha_version ) {
		update_option( 'um_recaptcha_version', um_recaptcha_version );
	}

	//run setup
	if ( ! class_exists( 'um_ext\um_recaptcha\core\Recaptcha_Setup' ) )
		require_once um_recaptcha_path . 'includes/core/class-recaptcha-setup.php';

	$recaptcha_setup = new um_ext\um_recaptcha\core\Recaptcha_Setup();
	$recaptcha_setup->run_setup();

