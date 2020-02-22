<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Display badges in Member Directories
 */
function um_mycred_show_badges_members( $profile_id, $args ) {
	if ( !UM()->options()->get( 'mycred_show_badges_in_members' ) ) {
		return;
	}
	echo '<div class="um-header" style="border:none;margin:initial;padding:initial;min-height:initial;">' . UM()->myCRED_API()->show_badges( $profile_id ) . '</div>';
}
add_action( 'um_members_just_after_name', 'um_mycred_show_badges_members', 20, 2 );


/**
 * Display badges in header
 */
function um_mycred_show_badges_header() {
	if ( ! UM()->options()->get('mycred_show_badges_in_header') ) {
		return;
	}
	echo UM()->myCRED_API()->show_badges( um_profile_id() );
}
add_action( 'um_after_profile_header_name', 'um_mycred_show_badges_header' );


/**
 * Default badges tab
 *
 * @param $args
 */
function um_profile_content_badges_default( $args ) {
	echo UM()->myCRED_API()->show_badges( um_profile_id() );
}
add_action( 'um_profile_content_badges_default', 'um_profile_content_badges_default' );


/**
 * Show user badges
 *
 * @param $args
 */
function um_profile_content_badges_my_badges( $args ) {
	echo UM()->myCRED_API()->show_badges( um_profile_id() );
}
add_action( 'um_profile_content_badges_my_badges', 'um_profile_content_badges_my_badges' );


/**
 * Show all badges
 *
 * @param $args
 */
function um_profile_content_badges_all_badges( $args ) {
	echo UM()->myCRED_API()->show_badges_all( 2 );
}
add_action( 'um_profile_content_badges_all_badges', 'um_profile_content_badges_all_badges' );