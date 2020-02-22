<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<p><strong><?php _e('If you do not provide custom styling here, defaults will be used.','twodayssss'); ?></strong></p>

	<?php UM()->admin_forms( array(
		'class'		=> 'um-form-notice-styling um-half-column',
		'prefix_id'	=> 'notice',
		'fields' => array(
			array(
				'id'		    => '_um_min_width',
				'type'		    => 'text',
				'size'		    => 'small',
				'label'		    => __( 'Notice Minimum Width', 'twodayssss' ),
				'tooltip'		=> __( 'Set a minimum width for notice wrapper','twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_min_width', null, 'na'),
			),
			array(
				'id'		    => '_um_bgcolor',
				'type'		    => 'color',
				'label'		    => __( 'Notice background color', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_bgcolor', null, 'na'),
			),
			array(
				'id'		    => '_um_textcolor',
				'type'		    => 'color',
				'label'		    => __( 'Notice text color', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_textcolor', null, 'na'),
			),
			array(
				'id'		    => '_um_fontsize',
				'type'		    => 'text',
				'size'		    => 'small',
				'label'		    => __( 'Notice font size', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_fontsize', null, 'na'),
			),
			array(
				'id'		    => '_um_icon',
				'type'		    => 'icon',
				'label'		    => __( 'Notice Icon', 'twodayssss' ),
				'tooltip'		=> __( 'You can optionally add an icon to this notice', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value( '_um_icon', null, 'na' ),
			),
			array(
				'id'		    => '_um_iconcolor',
				'type'		    => 'color',
				'label'		    => __( 'Notice Icon color', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_iconcolor', null, 'na'),
			),
			array(
				'id'		    => '_um_closeiconcolor',
				'type'		    => 'color',
				'label'		    => __( 'Notice Close Icon color', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_closeiconcolor', null, 'na'),
			),
			array(
				'id'		    => '_um_border',
				'type'		    => 'text',
				'size'			=> 'medium',
				'label'		    => __( 'Border', 'twodayssss' ),
				'tooltip'		=> __( 'Enter border css here', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_border', null, 'na'),
			),
			array(
				'id'		    => '_um_border_radius',
				'type'		    => 'text',
				'size'			=> 'small',
				'label'		    => __( 'Border Radius', 'twodayssss' ),
				'tooltip'		=> __( 'Enter border radius here. e.g. 3px', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_border_radius', null, 'na'),
			),
			array(
				'id'		    => '_um_boxshadow',
				'type'		    => 'text',
				'size'			=> 'small',
				'label'		    => __( 'Box Shadow', 'twodayssss' ),
				'tooltip'		=> __( 'Change this If you want to apply a box-shadow to the notice box', 'twodayssss' ),
				'value'		    => UM()->query()->get_meta_value('_um_boxshadow', null, 'na'),
			)
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>