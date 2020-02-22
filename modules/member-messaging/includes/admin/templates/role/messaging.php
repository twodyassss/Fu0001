<div class="um-admin-metabox">

	<?php $role = $object['data'];
	
	UM()->admin_forms( array(
		'class'		=> 'um-role-messages um-half-column',
		'prefix_id'	=> 'role',
		'fields' => array(
			array(
				'id'            => '_um_enable_messaging',
				'type'          => 'checkbox',
				'label'         => __( 'Enable Messaging feature?', 'twodayssss' ),
				'tooltip'       => __( 'Enable or disable messaging feature for this role', 'twodayssss' ),
				'value'         => isset( $role['_um_enable_messaging'] ) ? $role['_um_enable_messaging'] : 1,
			),
			array(
				'id'            => '_um_can_start_pm',
				'type'          => 'checkbox',
				'label'         => __( 'Can start conversations?','twodayssss' ),
				'tooltip'       => __( 'Can this role start conversation with other users?', 'twodayssss' ),
				'value'         => isset( $role['_um_can_start_pm'] ) ? $role['_um_can_start_pm'] : 1,
				'conditional'   => array( '_um_enable_messaging', '=', 1 )
			),
			array(
				'id'            => '_um_can_read_pm',
				'type'          => 'checkbox',
				'label'         => __( 'Can read private messages?','twodayssss' ),
				'tooltip'       => __( 'Can this role read private messages from other users?', 'twodayssss' ),
				'value'         => isset( $role['_um_can_read_pm'] ) ? $role['_um_can_read_pm'] : 1,
				'conditional'   => array( '_um_enable_messaging', '=', 1 )
			),
			array(
				'id'            => '_um_can_reply_pm',
				'type'          => 'checkbox',
				'label'         => __( 'Can reply private messages?','twodayssss' ),
				'tooltip'       => __( 'Turn this off to disable reply ability for this role', 'twodayssss' ),
				'value'         => isset( $role['_um_can_reply_pm'] ) ? $role['_um_can_reply_pm'] : 1,
				'conditional'   => array( '_um_can_read_pm', '=', 1 )
			),
			array(
				'id1'           => '_um_pm_max_messages',
				'id2'           => '_um_pm_max_messages_tf',
				'type'          => 'inline_texts',
				'size'          => 'small',
				'mask'          => '%s ' . __( 'per', 'twodayssss' ) . ' %s ' . __( 'Days', 'twodayssss' ),
				'label'         => __( 'Maximum number of messages they can send', 'twodayssss' ),
				'value1'        => isset( $role['_um_pm_max_messages'] ) ? $role['_um_pm_max_messages'] : '',
				'value2'        => isset( $role['_um_pm_max_messages_tf'] ) ? $role['_um_pm_max_messages_tf'] : '',
				'conditional'   => array( '_um_enable_messaging', '=', 1 )
			)
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>