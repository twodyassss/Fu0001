<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-field um-field-type_terms_conditions"  data-key="use_terms_conditions_agreement" style="display: block;padding:0px">
	<div class="um-field-area">
		<div class="um-terms-conditions-content" style="display:none">
			<?php if ( ! empty( $args['use_terms_conditions_content_id'] ) ) {
				$um_content_query = get_post( $args['use_terms_conditions_content_id'] );
				if ( isset( $um_content_query ) ) { ?>

					<a href="javascript:void(0);" class="um-hide-terms">
						<?php echo ! empty( $args['use_terms_conditions_toggle_hide'] ) ? $args['use_terms_conditions_toggle_hide'] : __( 'Hide Terms', 'twodayssss' ); ?>
					</a>

					<?php echo apply_filters( 'um_terms_conditions_tc_page_content', $um_content_query->post_content, $args );
				}
			}  ?>
		</div>
		<a href="javascript:void(0);" class="um-toggle-terms" data-toggle-state="hidden"
		   data-toggle-show="<?php echo ! empty( $args['use_terms_conditions_toggle_show'] ) ? esc_attr( $args['use_terms_conditions_toggle_show'] ) : esc_attr__( 'Show Terms', 'twodayssss' ); ?>"
		   data-toggle-hide="<?php echo ! empty( $args['use_terms_conditions_toggle_hide'] ) ? esc_attr( $args['use_terms_conditions_toggle_hide'] ) : esc_attr__( 'Hide Terms', 'twodayssss' ); ?>">
			<?php echo ! empty( $args['use_terms_conditions_toggle_show'] ) ? $args['use_terms_conditions_toggle_show'] : __( 'Show Terms', 'twodayssss' ); ?>
		</a>
	</div>
	<div class="um-field-area">

		<label class="um-field-checkbox">
			<input type="checkbox" name="use_terms_conditions_agreement" value="1">
			<span class="um-field-checkbox-state">
				<i class="um-icon-android-checkbox-outline-blank"></i>
			</span>
			<span class="um-field-checkbox-option">
				<?php echo ! empty( $args['use_terms_conditions_agreement'] ) ? $args['use_terms_conditions_agreement'] :  __( 'Please confirm that you agree to our terms & conditions', 'twodayssss' ); ?>
			</span>
		</label>
		<div class="um-clear"></div>

		<?php $errors = UM()->form()->errors;

		if ( isset( $errors['use_terms_conditions_agreement'] ) ) {

			$error_message = ! empty( $args['use_terms_conditions_error_text'] ) ? $args['use_terms_conditions_error_text'] :  __( 'You must agree to our terms & conditions','twodayssss' ); ?>

			<p class="um-notice err">
				<i class="um-icon-ios-close-empty" onclick="jQuery(this).parent().fadeOut();"></i><?php echo $error_message ?>
			</p>
			<br />
		<?php } ?>

		<div class="um-clear"></div>
	</div>
</div>