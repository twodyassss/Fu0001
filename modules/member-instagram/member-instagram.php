<?php
/**
 * Module Name: Member - Instagram
 */

defined( 'ABSPATH' ) || exit;

define( 'um_instagram_url', plugin_dir_url( __FILE__ ) );
define( 'um_instagram_path', plugin_dir_path( __FILE__ ) );

define( 'um_instagram_version', '2.10.3' );

require_once um_instagram_path . 'includes/core/member-instagram-init.php';

	//first install
	$version = get_option( 'um_instagram_version' );
	if ( ! $version ) {
		update_option( 'um_instagram_last_version_upgrade', um_instagram_version );
	}

	if ( $version != um_instagram_version ) {
		update_option( 'um_instagram_version', um_instagram_version );
	}

	//run setup
	if ( ! class_exists( 'um_ext\um_instagram\core\Instagram_Setup' ) )
		require_once um_instagram_path . 'includes/core/class-instagram-setup.php';

	$instagram_setup = new um_ext\um_instagram\core\Instagram_Setup();
	$instagram_setup->run_setup();

