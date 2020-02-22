<?php
/*
 *	Module Name: member online
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'um_online_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_online_path', trailingslashit( __DIR__ ) );
define( 'um_online_plugin', 'member-online/member-online.php' );
define( 'um_online_version', '2.1.2' );

require_once um_online_path . 'includes/core/member-online-init.php';
		//first install
		$version = get_option( 'um_online_version' );
		if ( ! $version ) {
			update_option( 'um_online_last_version_upgrade', um_online_version );
		}

		if ( $version != um_online_version ) {
			update_option( 'um_online_version', um_online_version );
		}

//run setup
if ( ! class_exists( 'um_ext\um_online\core\Online_Setup' ) ) 
	require_once um_online_path . 'includes/core/class-online-setup.php';

$online_setup = new um_ext\um_online\core\Online_Setup();
$online_setup->run_setup();