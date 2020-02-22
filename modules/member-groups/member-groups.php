<?php
/*
Module Name: Member - Groups
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'um_groups_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_groups_path', trailingslashit( __DIR__ ) );

define( 'um_groups_version', '2.10.6' );

require_once um_groups_path . 'includes/core/member-groups-init.php';

	//first install
	$version = get_option( 'um_groups_version' );
	if ( ! $version ) {
		update_option( 'um_groups_last_version_upgrade', um_groups_version );
	}

	if ( $version != um_groups_version ) {
		update_option( 'um_groups_version', um_groups_version );
	}
//run setup
	if ( ! class_exists( 'um_ext\um_groups\core\Groups_Setup' ) ) {
		require_once um_groups_path . 'includes/core/class-groups-setup.php';
	}
	$groups_setup = new um_ext\um_groups\core\Groups_Setup();
	$groups_setup->run_setup();