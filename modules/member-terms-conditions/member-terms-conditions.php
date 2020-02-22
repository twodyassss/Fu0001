<?php
/*
Module Name: Member - Terms & Conditions
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'um_terms_conditions_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_terms_conditions_path', trailingslashit( __DIR__ ) );
define( 'um_terms_conditions_plugin', 'member-terms-conditions/member-terms-conditions.php' );
define( 'um_terms_conditions_version', '2.1.2' );

require_once um_terms_conditions_path . 'includes/core/member-terms-conditions-init.php';

	//first install
	$version = get_option( 'um_terms_conditions_version' );
	if ( ! $version ) {
		update_option( 'um_terms_conditions_last_version_upgrade', um_terms_conditions_version );
	}

	if ( $version != um_terms_conditions_version ) {
		update_option( 'um_terms_conditions_version', um_terms_conditions_version );
	}