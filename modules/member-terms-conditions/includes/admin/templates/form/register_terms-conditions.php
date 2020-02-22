<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<?php $options = array(
		'' => __( 'Select page', 'twodayssss' )
	);

	$pages = get_pages();
	foreach ( $pages as $page ) {
		$options[ $page->ID ] = $page->post_title;
	}

	UM()->admin_forms( array(
		'class'     => 'um-form-register-terms-conditions um-top-label',
		'prefix_id' => 'form',
		'fields'    => array(
			array(
				'id'        => '_um_register_use_terms_conditions',
				'type'      => 'select',
				'label'     => __( 'Enable on this form', 'twodayssss' ),
				'value'     => UM()->query()->get_meta_value('_um_register_use_terms_conditions', null, '' ),
				'options'   => array(
					'0' => __( 'No', 'twodayssss' ),
					'1' => __( 'Yes', 'twodayssss' )
				),
			),
			array(
				'id'            => '_um_register_use_terms_conditions_content_id',
				'type'          => 'select',
				'label'         => __( 'Content', 'twodayssss' ),
				'value'         => UM()->query()->get_meta_value('_um_register_use_terms_conditions_content_id', null, '' ),
				'options'       => $options,
				'conditional'   => array( '_um_register_use_terms_conditions', '=', '1' )
			),
			array(
				'id'            => '_um_register_use_terms_conditions_toggle_show',
				'type'          => 'text',
				'label'         => __( 'Toggle Show text', 'twodayssss' ),
				'placeholder'   => __( 'Show Terms','twodayssss' ),
				'value'         => UM()->query()->get_meta_value('_um_register_use_terms_conditions_toggle_show', null, __( 'Show Terms', 'twodayssss' ) ),
				'conditional'   => array( '_um_register_use_terms_conditions', '=', '1' )
			),
			array(
				'id'            => '_um_register_use_terms_conditions_toggle_hide',
				'type'          => 'text',
				'label'         => __( 'Toggle Hide text', 'twodayssss' ),
				'placeholder'   => __( 'Hide Terms', 'twodayssss' ),
				'value'         => UM()->query()->get_meta_value('_um_register_use_terms_conditions_toggle_hide', null, __( 'Hide Terms', 'twodayssss' ) ),
				'conditional'   => array( '_um_register_use_terms_conditions', '=', '1' )
			),
			array(
				'id'            => '_um_register_use_terms_conditions_agreement',
				'type'          => 'text',
				'label'         => __( 'Checkbox agreement description', 'twodayssss' ),
				'placeholder'   => __( 'Please confirm that you agree to our terms & conditions', 'twodayssss' ),
				'value'         => UM()->query()->get_meta_value('_um_register_use_terms_conditions_agreement', null, __( 'Checkbox agreement description', 'twodayssss' ) ),
				'conditional'   => array( '_um_register_use_terms_conditions', '=', '1' )
			),
			array(
				'id'            => '_um_register_use_terms_conditions_error_text',
				'type'          => 'text',
				'label'         => __( 'Error Text', 'twodayssss' ),
				'placeholder'   => __( 'You must agree to our terms & conditions', 'twodayssss' ),
				'value'         => UM()->query()->get_meta_value('_um_register_use_terms_conditions_error_text', null, __( 'You must agree to our terms & conditions', 'twodayssss' ) ),
				'conditional'   => array( '_um_register_use_terms_conditions', '=', '1' )
			)
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>