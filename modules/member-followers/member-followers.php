<?php
/*
Module Name: Member - Followers
*/
define( 'um_followers_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_followers_path', trailingslashit( __DIR__ ) );
define( 'um_followers_plugin', 'member-followers/member-followers.php' );
define( 'um_followers_version', '2.10.5' );

require_once um_followers_path . 'includes/core/member-followers-init.php';

//first install
$version = get_option( 'um_followers_version' );
	if ( ! $version ) {
		update_option( 'um_followers_last_version_upgrade', um_followers_version );
	}

	if ( $version != um_followers_version ) {
		update_option( 'um_followers_version', um_followers_version );
	}

	//run setup
	if ( ! class_exists( 'um_ext\um_followers\core\Followers_Setup' ) ) {
		require_once um_followers_path . 'includes/core/class-followers-setup.php';
	}
$followers_setup = new um_ext\um_followers\core\Followers_Setup();
$followers_setup->run_setup();
