<?php
/*
Member Name: Member - WooCommerce
*/

define( 'um_woocommerce_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_woocommerce_path', trailingslashit( __DIR__ ) );
define( 'um_woocommerce_plugin', 'member-woocommerce/member-woocommerce.php' );
define( 'um_woocommerce_version', '2.10.8' );

require_once um_woocommerce_path . 'includes/core/member-woocommerce-init.php';

	//first install
	$version = get_option( 'um_woocommerce_version' );
	if ( ! $version )
		update_option( 'um_woocommerce_last_version_upgrade', um_woocommerce_version );

	if ( $version != um_woocommerce_version )
		update_option( 'um_woocommerce_version', um_woocommerce_version );
	
	//run setup
	if ( ! class_exists( 'um_ext\um_woocommerce\core\WooCommerce_Setup' ) )
		require_once um_woocommerce_path . 'includes/core/class-woocommerce-setup.php';

$woocommerce_setup = new um_ext\um_woocommerce\core\WooCommerce_Setup();
$woocommerce_setup->run_setup();
