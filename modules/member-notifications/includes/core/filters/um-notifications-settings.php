<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend settings
 *
 * @param array $settings
 *
 * @return array
 */
function um_notifications_settings( $settings ) {
	
	$key = ! empty( $settings['extensions']['sections'] ) ? 'notifications' : '';
	$settings['extensions']['sections'][$key] = array(
		'title'     => __( 'Notifications', 'twodayssss' ),
		'fields'    => array(
			array(
				'id'        => 'realtime_notify',
				'type'      => 'checkbox',
				'label'     => __( 'Enable real-time instant notification', 'twodayssss' ),
				'tooltip'   => __( 'Turn off please If your server is getting some load.', 'twodayssss' ),
			),
			array(
				'id'            => 'realtime_notify_timer',
				'type'          => 'text',
				'label'         => __( 'How often do you want the ajax notifier to check for new notifications? (in seconds)', 'twodayssss'),
				'validate'      => 'numeric',
				'conditional'   => array( 'realtime_notify', '=', 1 ),
				'size'          => 'small',
			),
			array(
				'id'            => 'notify_pos',
				'type'          => 'select',
				'label'         => __( 'Where should the notification icon appear?', 'twodayssss' ),
				'options'       => array(
					'right' => __( 'Right bottom', 'twodayssss' ),
					'left'  => __( 'Left bottom', 'twodayssss' )
				),
				'placeholder'   => __( 'Select...', 'twodayssss' ),
				'size'          => 'small',
			),
			array(
				'id'        => 'notification_icon_visibility',
				'type'      => 'checkbox',
				'label'     => __( 'Always display the notification icon', 'twodayssss' ),
				'tooltip'   => __( 'If turned off, the icon will only show when there\'s a new notification.', 'twodayssss' ),
			),
			array(
				'id'        => 'notification_sound',
				'type'      => 'checkbox',
				'label'     => __( 'Notification sound', 'twodayssss' ),
				'tooltip'   => __( 'Play sound when new notification appear. It may not work in Chrome due to Autoplay Policy.', 'twodayssss' ),
				'conditional'   => array( 'realtime_notify', '=', 1 ),
			),
			array(
				'id'        => 'account_tab_webnotifications',
				'type'      => 'checkbox',
				'label'     => __( 'Account Tab', 'twodayssss' ),
				'tooltip'   => __( 'Show or hide an account tab that shows the web notifications.', 'twodayssss' ),
			)
		)
	);

	foreach( UM()->Notifications_API()->api()->get_log_types() as $k => $desc ) {

		$settings['extensions']['sections'][ $key ]['fields'] = array_merge( $settings['extensions']['sections'][ $key ]['fields'], array(
			array(
				'id'    => 'log_' . $k,
				'type'  => 'checkbox',
				'label' => $desc['title'],
			),
			array(
				'id'            => 'log_' . $k . '_template',
				'type'          => 'textarea',
				'label'         => __( 'Template', 'twodayssss' ),
				'conditional'   => array('log_' . $k, '=', 1),
				'rows'          => 2,
			)
		) );
	}

	return $settings;
}
add_filter( 'um_settings_structure', 'um_notifications_settings', 10, 1 );