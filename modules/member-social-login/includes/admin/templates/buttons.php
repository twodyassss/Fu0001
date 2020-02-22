<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<h4><?php _e( 'Provider Settings','twodayssss' ); ?></h4>

	<?php $fields = array();
	foreach( UM()->Social_Login_API()->networks as $provider => $array ) {
		$fields[] = array(
			'id'		    => '_um_enable_' . $provider,
			'type'		    => 'checkbox',
			'label'    		=> sprintf( __( 'Enable <b>%s</b>', 'twodayssss' ), $array['name'] ),
			'value' 		=> UM()->query()->get_meta_value( '_um_enable_' . $provider, null, 1 )
		);
	}

	UM()->admin_forms( array(
		'class'		=> 'um-social-login-networks um-half-column',
		'prefix_id'	=> 'social_login',
		'fields' 	=> $fields
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
	<h4><?php _e( 'General Settings','twodayssss' ); ?></h4>

	<?php UM()->admin_forms( array(
		'class'		=> 'um-social-login-general um-half-column',
		'prefix_id'	=> 'social_login',
		'fields' 	=> array(
			array(
				'id'		=> '_um_assigned_role',
				'type'		=> 'select',
				'label'    	=> __( 'Assign Role','twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value( '_um_assigned_role' ),
				'options' 	=> UM()->roles()->get_roles()
			),
			array(
				'id'		=> '_um_show_for_members',
				'type'		=> 'checkbox',
				'label'    	=> __( 'Show for logged-in users?', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value( '_um_show_for_members', null, 1 ),
			),
			array(
				'id'		=> '_um_keep_signed_in',
				'type'		=> 'checkbox',
				'label'    	=> __( 'Keep user signed in?', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value( '_um_keep_signed_in', null, 1 ),
			)
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
	<h4><?php _e('Button Appearance', 'twodayssss'); ?></h4>

	<?php UM()->admin_forms( array(
		'class'		=> 'um-social-login-button um-half-column',
		'prefix_id'	=> 'social_login',
		'fields' 	=> array(
			array(
				'id'		=> '_um_show_icons',
				'type'		=> 'checkbox',
				'label'    	=> __( 'Show icon in the social login button?', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value( '_um_show_icons', null, 1 ),
			),
			array(
				'id'		=> '_um_show_labels',
				'type'		=> 'checkbox',
				'label'    	=> __( 'Show label in the social login button?', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value( '_um_show_labels', null, 1 ),
			),
			array(
				'id'		=> '_um_fontsize',
				'type'		=> 'text',
				'size'		=> 'small',
				'placeholder' => '15px',
				'label'    	=> __( 'Font Size', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value('_um_fontsize', null, 'na' ),
			),
			array(
				'id'		=> '_um_iconsize',
				'type'		=> 'text',
				'size'		=> 'small',
				'placeholder' => '18px',
				'label'    	=> __( 'Icon Size', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value('_um_iconsize', null, 'na' ),
			),
			array(
				'id'		=> '_um_button_style',
				'type'		=> 'select',
				'label'    	=> __( 'Button Style','twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value( '_um_button_style' ),
				'options' 	=> array(
					'default' => __( 'One button per line', 'twodayssss' ),
					'responsive' => __( 'Responsive','twodayssss' ),
					'floated' => __( 'Floated', 'twodayssss' )
				)
			),
			array(
				'id'		=> '_um_button_min_width',
				'type'		=> 'text',
				'size'		=> 'small',
				'placeholder' => 'e.g. 205px',
				'label'    	=> __( 'Button Min Width', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value('_um_button_min_width', null, 'na' ),
			),
			array(
				'id'		=> '_um_button_padding',
				'type'		=> 'text',
				'size'		=> 'small',
				'placeholder' => '16px 20px',
				'label'    	=> __( 'Button Padding', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value('_um_button_padding', null, 'na' ),
			),
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
	<h4><?php _e('Container Appearance','twodayssss'); ?></h4>

	<?php UM()->admin_forms( array(
		'class'		=> 'um-social-login-container um-half-column',
		'prefix_id'	=> 'social_login',
		'fields' 	=> array(
			array(
				'id'		=> '_um_container_max_width',
				'type'		=> 'text',
				'size'		=> 'small',
				'placeholder' => '600px',
				'label'    	=> __( 'Icon Size', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value('_um_container_max_width', null, '600px' ),
			),
			array(
				'id'		=> '_um_margin',
				'type'		=> 'text',
				'size'		=> 'small',
				'placeholder' => '0px 0px 0px 0px',
				'label'    	=> __( 'Container Margin', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value('_um_margin', null, 'na' ),
			),
			array(
				'id'		=> '_um_padding',
				'type'		=> 'text',
				'size'		=> 'small',
				'placeholder' => '0px 0px 0px 0px',
				'label'    	=> __( 'Container Padding', 'twodayssss' ),
				'value' 	=> UM()->query()->get_meta_value('_um_padding', null, 'na' ),
			),
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>