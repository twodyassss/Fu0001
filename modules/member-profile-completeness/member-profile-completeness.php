<?php
/*
Member Name: Member - Profile Completeness
*/

define( 'um_profile_completeness_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_profile_completeness_path', trailingslashit( __DIR__ ) );
//define( 'um_profile_completeness_plugin', plugin_basename( __FILE__ ) );
define( 'um_profile_completeness_version', '2.10.1' );

require_once um_profile_completeness_path . 'includes/core/member-profile-completeness-init.php';

	//first install
	$version = get_option( 'um_profile_completeness_version' );
	if ( ! $version )
		update_option( 'um_profile_completeness_last_version_upgrade', um_profile_completeness_version );

	if ( $version != um_profile_completeness_version )
		update_option( 'um_profile_completeness_version', um_profile_completeness_version );
