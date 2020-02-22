<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * @param $settings
 *
 * @return mixed
 */
function um_friends_settings( $settings ) {

	$key = ! empty( $settings['extensions']['sections'] ) ? 'friends' : '';
	$settings['extensions']['sections'][$key] = array(
		'title'     => __( 'Friends', 'twodayssss' ),
		'fields'    => array(
			array(
				'id'    => 'friends_show_stats',
				'type'  => 'checkbox',
				'label' => __( 'Show friends stats in member directory', 'twodayssss' ),
			),
			array(
				'id'    => 'friends_show_button',
				'type'  => 'checkbox',
				'label' => __( 'Show friend button in member directory', 'twodayssss' ),
			)
		)
	);

	return $settings;
}
add_filter( 'um_settings_structure', 'um_friends_settings', 10, 1 );


/**
 * @param $settings
 * @param $key
 *
 * @return mixed
 */
function um_friends_activity_settings( $settings, $key ) {
	$settings['extensions']['sections'][ $key ]['fields'] = array_merge($settings['extensions']['sections'][ $key ]['fields'], array(
		array(
			'id'    => 'activity_friends_mention',
			'type'  => 'checkbox',
			'label' => __( 'Enable integration with friends to convert user names to user profile links automatically (mention users)?', 'twodayssss' ),
		),
		array(
			'id'    => 'activity_friends_users',
			'type'  => 'checkbox',
			'label' => __( 'Show only friends activity in the social wall', 'twodayssss' ),
		)
	) );

	return $settings;
}
add_filter( 'um_activity_settings_structure', 'um_friends_activity_settings', 10, 2 );


/**
 * @param $email_notifications
 *
 * @return mixed
 */
function um_friends_email_notifications( $email_notifications ) {
	$email_notifications['new_friend_request'] = array(
		'key'           => 'new_friend_request',
		'title'         => __( 'New Friend Request Notification','twodayssss' ),
		'subject'       => '{friend} wants to be friends with you on {site_name}',
		'body'          => 'Hi {receiver},<br /><br />' .
			'{friend} has just sent you a friend request on {site_name}.<br /><br />' .
			'View their profile to accept/reject this friendship request:<br />' .
			'{friend_profile}<br /><br />' .
			'This is an automated notification from {site_name}. You do not need to reply.',
		'description'   => __('Send a notification to user when they receive a new friend request','twodayssss'),
		'recipient'   => 'user',
		'default_active' => true
	);

	$email_notifications['new_friend'] = array(
		'key'           => 'new_friend',
		'title'         => __( 'New Friend Notification','twodayssss' ),
		'subject'       => '{friend} has accepted your friend request',
		'body'          => 'Hi {receiver},<br /><br />' .
			'You are now friends with {friend} on {site_name}.<br /><br />' .
			'View their profile:<br />' .
			'{friend_profile}<br /><br />' .
			'This is an automated notification from {site_name}. You do not need to reply.',
		'description'   => __('Send a notification to user when a friend request get approved','twodayssss'),
		'recipient'   => 'user',
		'default_active' => true
	);

	return $email_notifications;
}
add_filter( 'um_email_notifications', 'um_friends_email_notifications', 10, 1 );


/**
 * Adds a notification type
 *
 * @param $array
 *
 * @return mixed
 */
function um_friends_add_notification_type( $array ) {
	$array['new_friend_request'] = array(
		'title'         => __('User get a new friend request','twodayssss'),
		'template'      => __('<strong>{member}</strong> has sent you a friendship request'),
		'account_desc'  => __('When someone requests friendship','twodayssss'),
	);

	$array['new_friend'] = array(
		'title'         => __('User get a new friend','twodayssss'),
		'template'      => __('<strong>{member}</strong> has accepted your friendship request'),
		'account_desc'  => __('When someone accepts friendship','twodayssss'),
	);

	return $array;
}
add_filter( 'um_notifications_core_log_types', 'um_friends_add_notification_type', 200 );


/**
 * Adds a notification icon
 *
 * @param $output
 * @param $type
 *
 * @return string
 */
function um_friends_add_notification_icon( $output, $type ) {
	if ( $type == 'new_friend_request' ) {
		$output = '<i class="um-icon-android-person-add" style="color: #44b0ec"></i>';
	}

	if ( $type == 'new_friend' ) {
		$output = '<i class="um-icon-android-person" style="color: #44b0ec"></i>';
	}
	return $output;
}
add_filter( 'um_notifications_get_icon', 'um_friends_add_notification_icon', 10, 2 );