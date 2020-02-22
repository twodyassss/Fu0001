<?php
/*
Module Name: Member - Private Messages
*/

define( 'um_messaging_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_messaging_path', trailingslashit( __DIR__ ) );
define( 'um_messaging_plugin', 'member-messaging/member-messaging.php' );
define( 'um_messaging_version', '2.20.4' );

require_once um_messaging_path . 'includes/core/member-messaging-init.php';

	//first install
	$version = get_option( 'um_messaging_version' );
	if ( ! $version )
		update_option( 'um_messaging_last_version_upgrade', um_messaging_version );

	if ( $version != um_messaging_version )
		update_option( 'um_messaging_version', um_messaging_version );

//run setup
if ( ! class_exists( 'um_ext\um_messaging\core\Messaging_Setup' ) ) {
	require_once um_messaging_path . 'includes/core/class-messaging-setup.php';
}

	$messaging_setup = new um_ext\um_messaging\core\Messaging_Setup();
	$messaging_setup->run_setup();