<?php
if ( ! defined( 'myCRED_VERSION' ) ) exit;

/**
 * myCRED Shortcode: mycred_leaderboard
 */
if ( ! function_exists( 'mycred_render_shortcode_leaderboard' ) ) :
	function mycred_render_shortcode_leaderboard( $atts, $content = '' ) {

		$args = shortcode_atts( array(
			'number'       => 25,
			'order'        => 'DESC',
			'offset'       => 0,
			'type'         => MYCRED_DEFAULT_TYPE_KEY,
			'based_on'     => 'balance',
			'total'        => 0,
			'wrap'         => 'li',
			'template'     => '#%position% %user_profile_link% %cred_f%',
			'nothing'      => 'Leaderboard is empty',
			'current'      => 0,
			'exclude_zero' => 1,
			'timeframe'    => '',
			'to'    => ''
		), $atts, MYCRED_SLUG . '_leaderboard' );

		// Construct the leaderboard class
		$leaderboard = mycred_get_leaderboard( $args );

		// Just constructing the class will not yeld any results
		// We need to run the query to populate the leaderboard
		$leaderboard->get_leaderboard_results( (bool) $args['current'] );

		// Render and return
		return do_shortcode( $leaderboard->render( $args, $content ) );

	}
endif;
add_shortcode( MYCRED_SLUG . '_leaderboard', 'mycred_render_shortcode_leaderboard' );
