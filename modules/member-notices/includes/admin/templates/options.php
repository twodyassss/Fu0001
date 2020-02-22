<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<?php $current_roles = array();
	foreach ( UM()->roles()->get_roles() as $key => $value) {
		if ( UM()->query()->get_meta_value( '_um_roles', $key ) ) {
			$current_roles[] = $key;
		}
	}

	$fields = array(
		array(
			'id'		    => '_um_show_in_footer',
			'type'		    => 'checkbox',
			'label'		    => __( 'Allow this notice to appear in footer', 'twodayssss' ),
			'tooltip'		=> __( 'If turned off, this notice can only appear using shortcode method', 'twodayssss' ),
			'value'		    => UM()->query()->get_meta_value( '_um_show_in_footer', null, 1 ),
		),
		array(
			'id'		    => '_um_show_loggedout',
			'type'		    => 'checkbox',
			'label'		    => __( 'Display this notice to logged out users', 'twodayssss' ),
			'value'		    => UM()->query()->get_meta_value( '_um_show_loggedout', null, 'na' ),
		),
		array(
			'id'		    => '_um_show_loggedin',
			'type'		    => 'checkbox',
			'label'		    => __( 'Display this notice to logged in users', 'twodayssss' ),
			'value'		    => UM()->query()->get_meta_value( '_um_show_loggedin', null, 1 ),
		),
		array(
			'id'		    => '_um_roles',
			'type'		    => 'select',
			'label'		    => __( 'Which user roles can see this notice', 'twodayssss' ),
			'tooltip'		=> __( 'Leave blank to show to all user roles', 'twodayssss' ),
			'size'			=> 'medium',
			'multi'			=> true,
			'value'		    => ! empty( $current_roles ) ? $current_roles : array(),
			'options'		=> UM()->roles()->get_roles(),
			'conditional'   => array( '_um_show_loggedin', '=', 1 )
		),
		array(
			'id'		    => '_um_custom_field',
			'type'		    => 'select',
			'label'		    => __( 'Show If the user did not', 'twodayssss' ),
			'size'			=> 'medium',
			'value'		    => UM()->query()->get_meta_value( '_um_custom_field', null, '0' ),
			'options'		=> array(
				'0'	=> '--',
				'profile_photo'	=> __( 'Upload profile photo', 'twodayssss' ),
				'cover_photo'	=> __( 'Upload cover photo', 'twodayssss' ),
				'other'	=> __( 'Other', 'twodayssss' ),
			),
			'conditional'   => array( '_um_show_loggedin', '=', 1 )
		),
		array(
			'id'		    => '_um_custom_key',
			'type'		    => 'text',
			'label'		    => __( 'Show If the user did not fill that metakey','twodayssss' ),
			'value'		    => UM()->query()->get_meta_value('_um_custom_key', null, 'na'),
			'conditional'   => array( '_um_custom_field', '=', 'other' )
		),
		array(
			'id'		    => '_um_only_users',
			'type'		    => 'text',
			'label'		    => __( 'Show only to certain user(s)', 'twodayssss' ),
			'tooltip'		=> __( 'A comma seperated list of user IDs or usernames to show this notice for', 'twodayssss' ),
			'value'		    => UM()->query()->get_meta_value('_um_only_users', null, 'na'),
		),
	);

	if ( class_exists( 'Easy_Digital_Downloads' ) ) {
		$fields[] = array(
			'id'		    => '_um_edd_users',
			'type'		    => 'select',
			'label'		    => __( 'Show to shop users', 'twodayssss' ),
			'size'			=> 'medium',
			'value'		    => UM()->query()->get_meta_value('_um_edd_users'),
			'options'		=> array(
				'0'	=> __( 'All', 'twodayssss' ),
				'1'	=> __( 'Users who did not purchase anything', 'twodayssss' ),
				'2'	=> __( 'Users who made purchases','twodayssss' ),
			),
		);

		$fields[] = array(
			'id'		    => '_um_edd_users_amount',
			'type'		    => 'text',
			'label'		    => __( 'Spent at least (on purchases)','twodayssss' ),
			'size'			=> 'small',
			'value'		    => UM()->query()->get_meta_value( '_um_edd_users_amount', null, 'na' ),
			'conditional'   => array( '_um_edd_users', '=', '2' )
		);
	}

	UM()->admin_forms( array(
		'class'		=> 'um-form-notice-options um-half-column',
		'prefix_id'	=> 'notice',
		'fields' => $fields
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>