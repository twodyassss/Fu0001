<?php
/*
Module Name: Member - Verified Users
*/

define('um_verified_users_url',trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define('um_verified_users_path',trailingslashit( __DIR__ ) );
define('um_verified_users_version', '2.10.3' );

require_once um_verified_users_path . 'includes/core/member-verified-users-init.php';

    //first install
    $version = get_option( 'um_verified_users_version' );
    if ( ! $version )
        update_option( 'um_verified_users_last_version_upgrade', um_verified_users_version );

    if ( $version != um_verified_users_version )
        update_option( 'um_verified_users_version', um_verified_users_version );

    //run setup
    if ( ! class_exists( 'um_ext\um_verified_users\core\Verified_Users_Setup' ) )
        require_once um_verified_users_path . 'includes/core/class-verified-users-setup.php';

    $verified_users_setup = new um_ext\um_verified_users\core\Verified_Users_Setup();
    $verified_users_setup->run_setup();

