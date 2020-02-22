<?php
if ( ! defined( 'myCRED_VERSION' ) ) exit;

/**
 * myCRED Shortcode: mycred_give
 */
if ( ! function_exists( 'mycred_render_shortcode_give' ) ) :
	function mycred_render_shortcode_give( $atts, $content = '' ) {

		extract( shortcode_atts( array(
			'amount'  => '',
			'user_id' => 'current',
			'log'     => '',
			'ref'     => 'gift',
			'limit'   => 0,
			'type'    => MYCRED_DEFAULT_TYPE_KEY
		), $atts, MYCRED_SLUG . '_give' ) );

		if ( ! is_user_logged_in() && $user_id == 'current' )
			return $content;

		if ( ! mycred_point_type_exists( $type ) || apply_filters( 'mycred_give_run', true, $atts ) === false ) return $content;

		$mycred  = mycred( $type );
		$user_id = mycred_get_user_id( $user_id );
		$ref     = sanitize_key( $ref );
		$limit   = absint( $limit );

		// Check for exclusion
		if ( $mycred->exclude_user( $user_id ) ) return $content;

		// Limit
		if ( $limit > 0 && mycred_count_ref_instances( $ref, $user_id, $type ) >= $limit ) return $content;

		$mycred->add_creds(
			$ref,
			$user_id,
			$amount,
			$log,
			0,
			'',
			$type
		);

	}
endif;
add_shortcode( MYCRED_SLUG . '_give', 'mycred_render_shortcode_give' );
