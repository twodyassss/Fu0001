<?php
/*
Member Name: Member - myCRED
*/

define( 'um_mycred_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_mycred_path', trailingslashit( __DIR__ ) );
define( 'um_mycred_plugin', 'member-mycred/member-mycred.php' );
define( 'um_mycred_version', '2.10.5' );

require_once um_mycred_path . 'includes/core/member-mycred-init.php';

	//first install
	$version = get_option( 'um_mycred_version' );
	if ( ! $version ) {
		update_option( 'um_mycred_last_version_upgrade', um_mycred_version );
	}

	if ( $version != um_mycred_version ) {
		update_option( 'um_mycred_version', um_mycred_version );
	}

//run setup
if ( ! class_exists( 'um_ext\um_mycred\core\myCRED_Setup' ) ) {
	require_once um_mycred_path . 'includes/core/class-mycred-setup.php';
}

$mycred_setup = new um_ext\um_mycred\core\myCRED_Setup();
$mycred_setup->run_setup();
