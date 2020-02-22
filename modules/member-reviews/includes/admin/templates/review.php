<div class="um-admin-metabox um-admin-metabox-review">

	<?php $fields = array();

	if ( get_post_meta( get_the_ID(), '_flagged', true ) ) {
		$fields[] = array(
			'id'		    => '_flagged',
			'type'		    => 'select',
			'label'    		=> __( 'Flagged', 'twodayssss' ),
			'description'   => __( 'This review has been flagged. Change the status below.', 'twodayssss' ),
			'value' 		=> UM()->query()->get_meta_value( '_flagged' ),
			'options' 		=> array(
				'0'	=>	__( 'Reviewed', 'twodayssss' ),
				'1'	=>	__( 'Under Review', 'twodayssss' )
			),
		);
	}

	$fields[] = array(
		'id'		    => '_reviewer_id',
		'type'		    => 'from_review',
		'label'    		=> __( 'From', 'twodayssss' ),
		'value' 		=> get_post_meta( get_the_ID(), '_reviewer_id', true ),
	);

	$fields[] = array(
		'id'		    => '_user_id',
		'type'		    => 'to_review',
		'label'    		=> __( 'To', 'twodayssss' ),
		'value' 		=> get_post_meta( get_the_ID(), '_user_id', true ),
	);

	$fields[] = array(
		'id'		    => '_rating',
		'type'		    => 'rating',
		'label'    		=> __( 'Rating', 'twodayssss' ),
		'value' 		=> get_post_meta( get_the_ID(), '_rating', true ),
	);

	$fields[] = array(
		'id'		    => '_status',
		'type'		    => 'select',
		'label'    		=> __( 'Status', 'twodayssss' ),
		'value' 		=> UM()->query()->get_meta_value( '_status' ),
		'options' 		=> array(
			'0'	=>	__( 'Pending', 'twodayssss' ),
			'1'	=>	__( 'Approved', 'twodayssss' )
		),
	);

	UM()->admin_forms( array(
		'class'		=> 'um-form-review um-top-label',
		'prefix_id'	=> 'review',
		'fields' => $fields
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>