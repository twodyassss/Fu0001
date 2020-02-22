<?php
/*
Module Name: Member - User Photos
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'um_user_photos_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_user_photos_path', trailingslashit( __DIR__ ) );

define( 'um_user_photos_version', ' 2.10.2' );

require_once um_user_photos_path . 'includes/core/member-user-photos-init.php';

	//first install
	$version_old = get_option( 'um_user_photos_latest_version' );
	$version = get_option( 'um_user_photos_version' );
	if ( ! $version && ! $version_old )
		update_option( 'um_user_photos_last_version_upgrade', um_user_photos_version );

	if ( $version != um_user_photos_version )
		update_option( 'um_user_photos_version', um_user_photos_version );


	//run setup
	if ( ! class_exists( 'um_ext\um_user_photos\core\User_Photos_Setup' ) )
		require_once um_user_photos_path . 'includes/core/class-user-photos-setup.php';

	$user_photos_setup = new um_ext\um_user_photos\core\User_Photos_Setup();
	$user_photos_setup->run_setup();