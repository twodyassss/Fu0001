<?php
/*
module Name: Member - User Tags
*/

define('um_user_tags_url',trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define('um_user_tags_path',trailingslashit( __DIR__ ) );
define('um_user_tags_version', '2.10.9' );

require_once um_user_tags_path . 'includes/core/member-user-tags-init.php';

$version = get_option( 'um_user_tags_version' );
		if ( ! $version )
			update_option( 'um_user_tags_last_version_upgrade', um_user_tags_version );

		if ( $version != um_user_tags_version )
			update_option( 'um_user_tags_version', um_user_tags_version );
		//var_dump($version);
//run setup
if ( ! class_exists( 'um_ext\um_user_tags\core\User_Tags_Setup' ) ) {
	require_once um_user_tags_path . 'includes/core/class-user-tags-setup.php';
}

$user_tags_setup = new um_ext\um_user_tags\core\User_Tags_Setup();
$user_tags_setup->run_setup();
