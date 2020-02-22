<div class="um-admin-metabox">

	<?php $role = $object['data'];

	$fields = array(
		array(
			'id'		    => '_um_profilec',
			'type'		    => 'select',
			'label'		    => __( 'Enable profile completeness', 'twodayssss' ),
			'tooltip'	=> __( 'Turn on / off profile completeness features for this role', 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec'] ) ? $role['_um_profilec'] : 0,
			'options'		=> array(
				0	=> __( 'No', 'twodayssss' ),
				1	=> __( 'Yes', 'twodayssss' ),
			),
		),
		array(
			'id'		    => '_um_profilec_pct',
			'type'		    => 'text',
			'label'		    => __( 'Percentage (%) required for completion', 'twodayssss' ),
			'tooltip'	=> __( 'Consider the profile complete when the user completes (%) by filling profile information.', 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec_pct'] ) ? $role['_um_profilec_pct'] : 100,
			'conditional'	=> array( '_um_profilec', '=', '1' )
		),
		array(
			'id'		    => 'profilec-setup',
			'type'		    => 'completeness_fields',
			'value'		    => ! empty( $role['_um_profilec_pct'] ) ? $role['_um_profilec_pct'] : 100,
			'conditional'	=> array( '_um_profilec', '=', '1' )
		),
		array(
			'id'		    => '_um_profilec_upgrade_role',
			'type'		    => 'select',
			'label'		    => __( 'Upgrade to role automatically when profile is 100% complete', 'twodayssss' ),
			'tooltip'	=> __( 'Prevent user from browsing site If their profile completion is below the completion threshold set up above?', 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec_upgrade_role'] ) ? $role['_um_profilec_upgrade_role'] : 0,
			'options'		=> UM()->roles()->get_roles( __( 'Do not upgrade', 'twodayssss' ) ),
			'conditional'	=> array( '_um_profilec', '=', '1' )
		),
		array(
			'id'		    => '_um_profilec_prevent_browse',
			'type'		    => 'select',
			'label'		    => __( 'Require profile to be complete to browse the site?', 'twodayssss' ),
			'tooltip'	=> __( 'Prevent user from browsing site If their profile completion is below the completion threshold set up above?', 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec_prevent_browse'] ) ? $role['_um_profilec_prevent_browse'] : 0,
			'conditional'	=> array( '_um_profilec', '=', '1' ),
			'options'		=> array(
				0	=> __( 'No', 'twodayssss' ),
				1	=> __( 'Yes', 'twodayssss' ),
			),
		),
		array(
			'id'		    => '_um_profilec_prevent_browse_exclude_pages',
			'type'		    => 'text',
			'label'		    => __( 'Allowed pages', 'twodayssss' ),
			'tooltip'	=> __( "Comma separated list of pages (use page ID), that don't depends on \"Require profile to be complete to browse the site\" option.", 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec_prevent_browse_exclude_pages'] ) ? $role['_um_profilec_prevent_browse_exclude_pages'] : '',
			'conditional'	=> array( '_um_profilec_prevent_browse', '=', '1' ),
		),
		array(
			'id'		    => '_um_profilec_prevent_profileview',
			'type'		    => 'select',
			'label'		    => __( 'Require profile to be complete to browse user profiles?', 'twodayssss' ),
			'tooltip'	=> __( 'Prevent user from browsing other profiles If their profile completion is below the completion threshold set up above?', 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec_prevent_profileview'] ) ? $role['_um_profilec_prevent_profileview'] : 0,
			'conditional'	=> array( '_um_profilec', '=', '1' ),
			'options'		=> array(
				0	=> __( 'No', 'twodayssss' ),
				1	=> __( 'Yes', 'twodayssss' ),
			),
		),
		array(
			'id'		    => '_um_profilec_prevent_comment',
			'type'		    => 'select',
			'label'		    => __( 'Require profile to be complete to leave a comment?', 'twodayssss' ),
			'tooltip'	=> __( 'Prevent user from leaving comments If their profile completion is below the completion threshold set up above?', 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec_prevent_comment'] ) ? $role['_um_profilec_prevent_comment'] : 0,
			'conditional'	=> array( '_um_profilec', '=', '1' ),
			'options'		=> array(
				0	=> __( 'No', 'twodayssss' ),
				1	=> __( 'Yes', 'twodayssss' ),
			),
		),
		array(
			'id'		    => '_um_profilec_prevent_bb',
			'type'		    => 'select',
			'label'		    => __( 'Require profile to be complete to create new bbPress topics/replies?', 'twodayssss' ),
			'tooltip'	=> __( 'Prevent user from adding participating in forum If their profile completion is below the completion threshold set up above?', 'twodayssss' ),
			'value'		    => ! empty( $role['_um_profilec_prevent_bb'] ) ? $role['_um_profilec_prevent_bb'] : 0,
			'conditional'	=> array( '_um_profilec', '=', '1' ),
			'options'		=> array(
				0	=> __( 'No', 'twodayssss' ),
				1	=> __( 'Yes', 'twodayssss' ),
			),
		),
	);

	$fields = apply_filters( 'um_profile_completeness_roles_metabox_fields', $fields, $role );

	UM()->admin_forms( array(
		'class'		=> 'um-role-profile-completeness um-top-label',
		'prefix_id'	=> 'role',
		'fields' => $fields
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>
