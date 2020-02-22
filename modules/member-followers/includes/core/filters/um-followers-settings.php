<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend settings
 *
 * @param $settings
 *
 * @return mixed
 */
function um_followers_settings( $settings ) {
	
	$key = ! empty( $settings['extensions']['sections'] ) ? 'followers' : '';
	$settings['extensions']['sections'][$key] = array(
		'title'     => __( 'Followers', 'twodayssss' ),
		'fields'    => array(
			array(
				'id'    => 'followers_show_stats',
				'type'  => 'checkbox',
				'label' => __( 'Show followers stats in member directory', 'twodayssss' ),
			),
			array(
				'id'    => 'followers_show_button',
				'type'  => 'checkbox',
				'label' => __( 'Show follow button in member directory', 'twodayssss' ),
			),
			array(
				'id'        => 'followers_allow_admin_to_follow',
				'type'      => 'checkbox',
				'label'     => __( 'Allow Administrators to follow users', 'twodayssss' ),
				'tooltip'   => __( 'Displays Follow buttons in profiles & member directory', 'twodayssss' ),
			)
		)
	);

	return $settings;
}
add_filter( 'um_settings_structure', 'um_followers_settings', 10, 1 );


/**
 * Extends activity settings
 * @param $settings
 * @param $key
 *
 * @return mixed
 */
function um_followers_activity_settings( $settings, $key ) {

	$settings['extensions']['sections'][ $key ]['fields'] = array_merge( $settings['extensions']['sections'][ $key ]['fields'], array(
		array(
			'id'    => 'activity_followers_mention',
			'type'  => 'checkbox',
			'label' => __( 'Enable integration with followers to convert user names to user profile links automatically (mention users)?', 'twodayssss' ),
		),
		array(
			'id'    => 'activity_followed_users',
			'type'  => 'checkbox',
			'label' => __( 'Show only followed users activity in the social wall', 'twodayssss' ),
		)
	) );

	return $settings;
}
add_filter( 'um_activity_settings_structure', 'um_followers_activity_settings', 10, 2 );


/**
 * Extends email notifications settings
 *
 * @param $email_notifications
 *
 * @return mixed
 */
function um_followers_email_notifications( $email_notifications ) {
	$email_notifications['new_follower'] = array(
		'key'               => 'new_follower',
		'title'             => __( 'New Follower Notification', 'twodayssss' ),
		'subject'           => '{follower} is now following you on {site_name}!',
		'body'              => 'Hi {followed},<br /><br />' .
		                       '{follower} has just followed you on {site_name}.<br /><br />' .
		                       'View his/her profile:<br />' .
		                       '{follower_profile}<br /><br />' .
		                       'Click on the following link to see your followers:<br />' .
		                       '{followers_url}<br /><br />' .
		                       'This is an automated notification from {site_name}. You do not need to reply.',
		'description'       => __( 'Send a notification to user when he receives a new review', 'twodayssss' ),
		'recipient'         => 'user',
		'default_active'    => true
	);

	return $email_notifications;
}
add_filter( 'um_email_notifications', 'um_followers_email_notifications', 10, 1 );


/**
 * Adds a notification type
 *
 * @param $array
 *
 * @return mixed
 */
function um_followers_add_notification_type( $array ) {
	$array['new_follow'] = array(
		'title'         => __( 'User get followed by a person', 'twodayssss' ),
		'template'      => '<strong>{member}</strong> has just followed you!',
		'account_desc'  => __( 'When someone follows me', 'twodayssss' ),
	);

	return $array;
}
add_filter( 'um_notifications_core_log_types', 'um_followers_add_notification_type', 200 );


/**
 * Adds a notification icon
 *
 * @param $output
 * @param $type
 *
 * @return string
 */
function um_followers_add_notification_icon( $output, $type ) {
	if ( $type == 'new_follow' ) {
		$output = '<i class="um-icon-android-person-add" style="color: #44b0ec"></i>';
	}
	return $output;
}
add_filter( 'um_notifications_get_icon', 'um_followers_add_notification_icon', 10, 2 );


/**
 * Extends wall privacy settings
 *
 * @param $array
 * @return mixed
 */
function um_followers_activity_wall_privacy_dropdown_values( $array ) {
	$array[3] = __( 'Followers', 'twodayssss' );
	$array[4] = __( 'People I follow', 'twodayssss' );

	return $array;
}
add_filter( 'um_activity_wall_privacy_dropdown_values', 'um_followers_activity_wall_privacy_dropdown_values', 10, 1 );