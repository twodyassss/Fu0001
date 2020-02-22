<?php
/*
Plugin: Member
*/

defined( 'ABSPATH' ) || exit;

define( 'um_url', trailingslashit( trailingslashit(get_template_directory_uri()) . $twodays->dir ) );
define( 'um_path', trailingslashit( __DIR__ ) );
define( 'um_plugin', plugin_basename( __FILE__ ) );
define( 'member_version', 'Version' );
define( 'member_plugin_name', 'Name' );

require_once 'includes/class-functions.php';
require_once 'includes/class-init.php';