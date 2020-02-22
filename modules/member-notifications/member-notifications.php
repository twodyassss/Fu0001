<?php
/*
Module Name: Member - Real-time Notifications
*/

define( 'um_notifications_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_notifications_path', trailingslashit( __DIR__ ) );

define( 'um_notifications_version', '2.10.2' );

require_once um_notifications_path . 'includes/core/member-notifications-init.php';

	//first install
	$version = get_option( 'um_notifications_version' );
	if ( ! $version )
		update_option( 'um_notifications_last_version_upgrade', um_notifications_version );

	if ( $version != um_notifications_version )
		update_option( 'um_notifications_version', um_notifications_version );
	//run setup
	if ( ! class_exists( 'um_ext\um_notifications\core\Notifications_Setup' ) )
		require_once um_notifications_path . 'includes/core/class-notifications-setup.php';
	
	$notifications_setup = new um_ext\um_notifications\core\Notifications_Setup();
	$notifications_setup->run_setup();