<?php
/*
Module Name: Member - User Bookmarks
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'um_user_bookmarks_url' , trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_user_bookmarks_path' , trailingslashit( __DIR__ ) );
define( 'um_user_bookmarks_plugin' , 'member-bookmarks/member-user-bookmarks.php' );
define( 'um_user_bookmarks_version' , '2.10.1' );

/**
 * Load text domain
 */

	require_once um_user_bookmarks_path . 'includes/core/member-user-bookmarks-functions.php';
	require_once um_user_bookmarks_path . 'includes/core/member-user-bookmarks-init.php';

	//first install
	$version_old = get_option( 'um_user_bookmarks_latest_version' );

	$version = get_option( 'um_user_bookmarks_version' );

	if ( ! $version && ! $version_old )
		update_option( 'um_user_bookmarks_last_version_upgrade', um_user_bookmarks_version );

	if ( $version != um_user_bookmarks_version )
		update_option( 'um_user_bookmarks_version', um_user_bookmarks_version );

	//run setup
	if ( ! class_exists( 'um_ext\um_user_bookmarks\core\Bookmark_Setup' ) )
		require_once um_user_bookmarks_path . 'includes/core/class-bookmark-setup.php';

	$user_bookmark_setup = new um_ext\um_user_bookmarks\core\Bookmark_Setup();

	$user_bookmark_setup->run_setup();

