<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<?php $status = UM()->options()->get( 'g_recaptcha_status' );

	if ( $status ) { ?>

		<p><?php _e( 'Google reCAPTCHA seems to be <strong style="color:#7ACF58">enabled</strong> by default.', 'twodayssss' ); ?></p>

	<?php } else { ?>

		<p><?php _e( 'Google reCAPTCHA seems to be <strong style="color:#C74A4A">disabled</strong> by default.', 'twodayssss' ); ?></p>

	<?php }

	UM()->admin_forms( array(
		'class'     => 'um-form-register-recaptcha um-top-label',
		'prefix_id' => 'form',
		'fields'    => array(
			array(
				'id'        => '_um_register_g_recaptcha_status',
				'type'      => 'select',
				'label'     => __( 'reCAPTCHA status on this form', 'twodayssss' ),
				'value'     => UM()->query()->get_meta_value( '_um_register_g_recaptcha_status', null, $status ),
				'options'   => array(
					'0' => __( 'No', 'twodayssss' ),
					'1' => __( 'Yes', 'twodayssss' )
				),
			)
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>