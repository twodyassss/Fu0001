<?php
/*
Module Name: Member - MailChimp
*/

define( 'um_mailchimp_url',trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_mailchimp_path',trailingslashit( __DIR__ ) );
define( 'um_mailchimp_version', '2.10.1' );

require_once um_mailchimp_path . 'includes/core/member-mailchimp-init.php';
 
    //first install
    $version = get_option( 'um_mailchimp_version' );
    if ( ! $version )
        update_option( 'um_mailchimp_last_version_upgrade', um_mailchimp_version );

    if ( $version != um_mailchimp_version )
        update_option( 'um_mailchimp_version', um_mailchimp_version );

    //run setup
    if ( ! class_exists( 'um_ext\um_mailchimp\core\Mailchimp_Setup' ) )
        require_once um_mailchimp_path . 'includes/core/class-mailchimp-setup.php';

    $mailchimp_setup = new um_ext\um_mailchimp\core\Mailchimp_Setup();
    $mailchimp_setup->run_setup();