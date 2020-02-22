<?php
/*
Module Name: Member - Social Activity
*/

define( 'um_activity_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_activity_path', trailingslashit( __DIR__ ) );

define( 'um_activity_version', '2.10.8' );

 require_once um_activity_path . 'includes/core/member-activity-init.php';

    //first install
    $version = get_option( 'um_activity_version' );
    if ( ! $version )
        update_option( 'um_activity_last_version_upgrade', um_activity_version );

    if ( $version != um_activity_version )
        update_option( 'um_activity_version', um_activity_version );
	//run setup
    if ( ! class_exists( 'um_ext\um_social_activity\core\Activity_Setup' ) )
        require_once um_activity_path . 'includes/core/class-activity-setup.php';

    $activity_setup = new um_ext\um_social_activity\core\Activity_Setup();
    $activity_setup->run_setup();