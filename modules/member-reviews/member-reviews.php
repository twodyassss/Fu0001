<?php
/*
Module Name: Member - User Reviews
*/
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'um_reviews_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_reviews_path', trailingslashit( __DIR__ ) );
define( 'um_reviews_plugin', 'member-reviews/member-reviews.php' );
define( 'um_reviews_version', '2.10.4' );

require_once um_reviews_path . 'includes/core/member-reviews-init.php';

	//first install
	$version = get_option( 'um_reviews_version' );
	if ( ! $version ) {
		update_option( 'um_reviews_last_version_upgrade', um_reviews_version );
	}

	if ( $version != um_reviews_version ) {
		update_option( 'um_reviews_version', um_reviews_version );
	}

	//run setup
	if ( ! class_exists( 'um_ext\um_reviews\core\Reviews_Setup' ) )
		require_once um_reviews_path . 'includes/core/class-reviews-setup.php';
	
	$reviews_setup = new um_ext\um_reviews\core\Reviews_Setup();
	$reviews_setup->run_setup();
