<?php
/*
Module Name: Ultimate Member - bbPress
*/

define( 'um_bbpress_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_bbpress_path', trailingslashit( __DIR__ ) );
define( 'um_bbpress_plugin', 'member-bbpress/member-bbpress.php' );
define( 'um_bbpress_version', '2.10.6' );

require_once um_bbpress_path . 'includes/core/member-bbpress-init.php';

	//first install
	$version = get_option( 'um_bbpress_version' );
	if ( ! $version )
		update_option( 'um_bbpress_last_version_upgrade', um_bbpress_version );

	if ( $version != um_bbpress_version )
		update_option( 'um_bbpress_version', um_bbpress_version );


	//run setup
	if ( ! class_exists( 'um_ext\um_bbpress\core\bbPress_Setup' ) )
		require_once um_bbpress_path . 'includes/core/class-bbpress-setup.php';

	$bbpress_setup = new um_ext\um_bbpress\core\bbPress_Setup();
	$bbpress_setup->run_setup();
