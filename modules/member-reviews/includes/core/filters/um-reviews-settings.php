<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend settings
 *
 * @param array $settings
 *
 * @return array
 */
function um_reviews_settings( $settings ) {

	$key = ! empty( $settings['extensions']['sections'] ) ? 'reviews' : '';
	$settings['extensions']['sections'][$key] = array(
		'title'     => __( 'Reviews', 'twodayssss' ),
		'fields'    => array(
			array(
				'id'    => 'members_show_rating',
				'type'  => 'checkbox',
				'label' => __( 'Show user rating in members directory','twodayssss' ),
			),
			array(
				'id'            => 'review_date_format',
				'type'          => 'select',
				'label'         => __( 'Review date format', 'twodayssss' ),
				'options'       => array(
					'j M Y'  => UM()->datetime()->get_time('j M Y'),
					'M j Y'  => UM()->datetime()->get_time('M j Y'),
					'j F Y'  => UM()->datetime()->get_time('j F Y'),
					'F j Y'  => UM()->datetime()->get_time('F j Y'),
				),
				'size'          => 'small'
			),
			array(
				'id'            => 'can_flag_review',
				'type'          => 'select',
				'label'         => __( 'Who can flag reviews', 'twodayssss' ),
				'options'       => array(
					'everyone'  => __( 'Everyone', 'twodayssss' ),
					'reviewed'  => __( 'Reviewed user only', 'twodayssss' ),
					'loggedin'  => __( 'All Logged-in Users', 'twodayssss' ),
				),
				'placeholder'   => __( 'Select...', 'twodayssss' ),
				'size'          => 'small',
			)
		)
	);

	return $settings;
}
add_filter( 'um_settings_structure', 'um_reviews_settings', 10, 1 );


/**
 * Extend email notifications
 *
 * @param array $email_notifications
 *
 * @return array
 */
function um_reviews_email_notifications( $email_notifications ) {
	$email_notifications['review_notice'] = array(
		'key'               => 'review_notice',
		'title'             => __( 'New Review Notification','twodayssss' ),
		'subject'           => 'You\'ve got a new {rating} review!',
		'body'              => 'Hi {display_name},<br /><br />' .
		                       'You\'ve received a new {rating} review from {reviewer}!<br /><br />' .
		                       'Here is the review content:<br /><br />' .
		                       '{review_content}<br /><br />' .
		                       '{reviews_link}<br /><br />' .
		                       'This is an automated notification from {site_name}. You do not need to reply.',
		'description'       => __('Send a notification to user when he receives a new review','twodayssss'),
		'recipient'         => 'user',
		'default_active'    => true
	);

	return $email_notifications;
}
add_filter( 'um_email_notifications', 'um_reviews_email_notifications', 10, 1 );


/**
 * Extend UM:Notifications notifications
 *
 * @param array $notifications_log
 *
 * @return array
 */
function um_reviews_notifications_log( $notifications_log ) {
	$notifications_log['user_review'] = array(
		'title'         => __( 'New user review', 'twodayssss' ),
		'template'      => __( '<strong>{member}</strong> has left you a new review. <span class="b1">"{review_excerpt}"</span>', 'twodayssss' ),
		'account_desc'  => __( 'When someone leaves me a review', 'twodayssss' ),
	);

	return $notifications_log;
}
add_filter( 'um_notifications_core_log_types', 'um_reviews_notifications_log', 10, 1 );