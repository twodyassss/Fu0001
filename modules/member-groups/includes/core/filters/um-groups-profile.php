<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * @param array $tabs
 *
 * @return array
 */
function um_groups_add_tabs( $tabs ) {
	$tabs['groups_list'] = array(
		'name'  => __( 'Groups', 'twodayssss' ),
		'icon'  => 'um-faicon-users',
	);

	return $tabs;
}
add_filter( 'um_profile_tabs', 'um_groups_add_tabs', 2000, 1 );


/**
 * Adds user-condition tab
 * @param array $tabs
 * @return array
 */
function um_groups_user_profile_tabs( $tabs ) {
	if ( um_user( 'groups_wall_off' ) ) {
		unset( $tabs['activity'] );
	}

	return $tabs;
}
add_filter( 'um_user_profile_tabs', 'um_groups_user_profile_tabs', 5, 1 );