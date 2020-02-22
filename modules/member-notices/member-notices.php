<?php
/*
Module Name: Member - Notices
*/

define( 'um_notices_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_notices_path', trailingslashit( __DIR__ ) );
define( 'um_notices_version', '2.10.4' );

require_once um_notices_path . 'includes/core/member-notices-init.php';
	

    //first install
    $version = get_option( 'um_notices_version' );
    if ( ! $version )
        update_option( 'um_notices_last_version_upgrade', um_notices_version );

    if ( $version != um_notices_version )
        update_option( 'um_notices_version', um_notices_version );


    //run setup
    if ( ! class_exists( 'um_ext\um_notices\core\Notices_Setup' ) )
        require_once um_notices_path . 'includes/core/class-notices-setup.php';

    $notices_setup = new um_ext\um_notices\core\Notices_Setup();
    $notices_setup->run_setup();
