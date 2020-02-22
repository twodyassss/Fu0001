<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<?php UM()->admin_forms( array(
		'class'		=> 'um-form-register-social um-top-label',
		'prefix_id'	=> 'form',
		'fields' => array(
			array(
				'id'		    => '_um_social_login_form',
				'type'		    => 'select',
				'label'    		=> __( 'Use this form in the overlay?', 'twodayssss' ),
				'tooltip'    	=> __( 'Please note only one registration form can be used for social overlay and this form should only be used for completion of social registration and not regular registration', 'twodayssss' ),
				'value' 		=> UM()->query()->get_meta_value( '_um_social_login_form', null, 0 ),
				'options' 		=> array(
					'0'	=>	__( 'No', 'twodayssss' ),
					'1'	=>	__( 'Yes', 'twodayssss' )
				),
			),
			array(
				'id'		    => '_um_register_show_social',
				'type'		    => 'select',
				'label'    		=> __( 'Show social connect on this form?', 'twodayssss' ),
				'value' 		=> UM()->query()->get_meta_value( '_um_register_show_social', null, 1 ),
				'options' 		=> array(
					'0'	=>	__('No','twodayssss'),
					'1'	=>	__('Yes','twodayssss')
				),
				'conditional'	=> array( '_um_social_login_form', '=', '0' )
			)
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>