<?php
namespace um_ext\um_messaging\admin\core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Messaging_Admin
 * @package um_ext\um_messaging\admin\core
 */
class Messaging_Admin {


	/**
	 * Messaging_Admin constructor.
	 */
	function __construct() {
		add_filter( 'um_admin_role_metaboxes', array( &$this, 'role_metabox' ), 10, 1 );
		add_filter( 'um_admin_extend_directory_options_general', array( &$this, 'member_directory_options' ), 10, 1 );

		add_filter( 'um_settings_structure', array( &$this, 'extend_settings' ), 10, 1 );
	}


	/**
	 * Creates options in Role page
	 *
	 * @param array $roles_metaboxes
	 *
	 * @return array
	 */
	function role_metabox( $roles_metaboxes ) {

		$roles_metaboxes[] = array(
			'id'        => "um-admin-form-messaging{" . um_messaging_path . "}",
			'title'     => __( 'Private Messages', 'twodayssss' ),
			'callback'  => array( UM()->metabox(), 'load_metabox_role' ),
			'screen'    => 'um_role_meta',
			'context'   => 'normal',
			'priority'  => 'default'
		);

		return $roles_metaboxes;
	}


	/**
	 * Admin options in directory
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	function member_directory_options( $fields ) {
		$additional_fields = array(
			array(
				'id'    => '_um_show_pm_button',
				'type'  => 'checkbox',
				'label' => __( 'Show message button in directory?', 'twodayssss' ),
				'value' => UM()->query()->get_meta_value( '_um_show_pm_button', null, 1 ),
			)
		);

		return array_merge( $fields, $additional_fields );
	}


	/**
	 * Extend UM settings
	 *
	 * @param $settings
	 *
	 * @return mixed
	 */
	function extend_settings( $settings ) {

		$key = ! empty( $settings['extensions']['sections'] ) ? 'messaging' : '';
		$settings['extensions']['sections'][ $key ] = array(
			'title'     => __( 'Private Messaging', 'twodayssss' ),
			'fields'    => array(
				array(
					'id'        => 'pm_unread_first',
					'type'      => 'checkbox',
					'label'     => __( 'Show unread messages first', 'twodayssss' ),
				),
				array(
					'id'        => 'pm_char_limit',
					'type'      => 'text',
					'label'     => __( 'Message character limit', 'twodayssss' ),
					'validate'  => 'numeric',
					'size'      => 'small',
				),
				array(
					'id'        => 'pm_block_users',
					'type'      => 'text',
					'label'     => __( 'Block users from sending/receiving messages', 'twodayssss' ),
					'tooltip'   => __( 'A comma seperated list of user IDs that cannot send/receive messages on the site.', 'twodayssss' ),
					'size'      => 'medium',
				),
				array(
					'id'            => 'pm_active_color',
					'type'          => 'color',
					'label'         => __( 'Primary color', 'twodayssss' ),
					'validate'      => 'color',
					'transparent'   => false,
				),
				array(
					'id'        => 'pm_coversation_refresh_timer',
					'type'      => 'text',
					'label'     => __( 'How often do you want the AJAX refresh conversation (in seconds)', 'twodayssss' ),
					'validate'  => 'numeric',
					'size'      => 'small',
				),
				array(
					'id'            => 'pm_notify_period',
					'type'          => 'select',
					'label'         => __( 'Send email notifications If user did not login for', 'twodayssss' ),
					'tooltip'       => __( 'Send email notifications about new messages if the user\'s last login time exceeds that period.', 'twodayssss' ),
					'options'       => array(
						3600    => __( '1 Hour', 'twodayssss' ),
						86400   => __( '1 Day', 'twodayssss' ),
						604800  => __( '1 Week', 'twodayssss' ),
						2592000 => __( '1 Month', 'twodayssss' ),
					),
					'placeholder'   => __( 'Select...', 'twodayssss' ),
					'size'          => 'small',
				),
				array(
					'id'            => 'pm_remind_period',
					'type'					=> 'text',
					'label'         => __( 'Send email notifications If user didn\'t read message for [n] hours', 'twodayssss' ),
					'tooltip'       => __( 'Send email notifications about unread message if the user didn\'t read it during that period.', 'twodayssss' ),
					'placeholder'   => __( '[n] hours', 'twodayssss' ),
					'validate'  => 'numeric',
					'size'      => 'small',
				),
				array(
					'id'            => 'pm_remind_limit',
					'type'					=> 'text',
					'label'         => __( 'Send email notifications not more then [m] times.', 'twodayssss' ),
					'tooltip'       => __( 'Email notifications about unread message will be send every [n] hours but no more then [m] times.', 'twodayssss' ),
					'placeholder'   => __( '[m] times', 'twodayssss' ),
					'validate'			=> 'numeric',
					'size'					=> 'small',
					'max'						=> 9,
				)
			)
		);

		return $settings;
	}
}