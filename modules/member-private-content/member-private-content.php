<?php
/*
Member Name: Member - Private Content
*/

define( 'um_private_content_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_private_content_path',  trailingslashit( __DIR__ ) );
define( 'um_private_content_version', '2.10.4');

require_once um_private_content_path . 'includes/core/member-private-content-init.php';

	//first install
	$version = get_option( 'um_private_content_version' );
	if ( ! $version ) {
		update_option( 'um_private_content_last_version_upgrade', um_private_content_version );
	}

	if ( $version != um_private_content_version ) {
		update_option( 'um_private_content_version', um_private_content_version );
	}

	//run setup
	if ( ! class_exists( 'um_ext\um_private_content\core\Private_Content_Setup' ) ) {
		require_once um_private_content_path . 'includes/core/class-private-content-setup.php';
	}

	$private_content_setup = new um_ext\um_private_content\core\Private_Content_Setup();
	$private_content_setup->run_setup();