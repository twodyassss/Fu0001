<?php
/*
Module Name: Member - Friends
*/

define( 'um_friends_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_friends_path', trailingslashit( __DIR__ ) );
define( 'um_friends_plugin', 'member-friends/member-friends.php');

define( 'um_friends_version', '2.10.3' );

require_once um_friends_path . 'includes/core/member-friends-init.php';

	//first install
	$version = get_option( 'um_friends_version' );
	if ( ! $version ) {
		update_option( 'um_friends_last_version_upgrade', um_friends_version );
	}

	if ( $version != um_friends_version ) {
		update_option( 'um_friends_version', um_friends_version );
	}

	//run setup
	if ( ! class_exists( 'um_ext\um_friends\core\Friends_Setup' ) ) {
		require_once um_friends_path . 'includes/core/class-friends-setup.php';
	}

	$friends_setup = new um_ext\um_friends\core\Friends_Setup();
	$friends_setup->run_setup();
