<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Default reviews tab
 *
 * @param $args
 */
function um_profile_content_reviews_default( $args ) {

	wp_enqueue_script( 'um_reviews' );
	wp_enqueue_style( 'um_reviews' );

	twodays_get_template( 'review-overview.php', um_reviews_plugin, $args, true );
	twodays_get_template( 'review-add.php', um_reviews_plugin, $args, true );
	twodays_get_template( 'review-edit.php', um_reviews_plugin, $args, true );

	UM()->Reviews_API()->api()->set_filter();

	$reviews = UM()->Reviews_API()->api()->get_reviews( um_profile_id() );
	if ( $reviews && is_array( $reviews ) ) {

		$args['reviews'] = $reviews;
		twodays_get_template( 'review-list.php', um_reviews_plugin, $args, true );

	} elseif ( $reviews === -1 ) {

		twodays_get_template( 'review-my.php', um_reviews_plugin, $args, true );

	} else {

		twodays_get_template( 'review-none.php', um_reviews_plugin, $args, true );

	}
}
add_action( 'um_profile_content_reviews_default', 'um_profile_content_reviews_default' );