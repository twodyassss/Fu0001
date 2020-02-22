<?php
/**
 * Template for the UM User Reviews, The Review actions.
 *
 * Page: "Profile", tab "Reviews"
 * Hook: 'um_review_front_actions'
 * Caller: um_review_front_actions() function
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-reviews/review-front-actions.php
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( UM()->Reviews_API()->api()->can_flag( $review_id ) ) { ?>

	<div class="um-reviews-flag"><a href="#"><i class="um-faicon-flag"></i> <span><?php _e( 'Report', 'twodayssss' ); ?></span></a></div>

<?php }

if ( $reviewer_id == $my_id && UM()->Reviews_API()->api()->already_reviewed( $user_id ) ) { ?>

	<div class="um-reviews-edit"><a href="#"><i class="um-faicon-pencil"></i> <span><?php _e( 'Edit', 'twodayssss' ); ?></span></a></div>

<?php } elseif ( UM()->Reviews_API()->api()->can_edit( $reviewer_id ) ) { ?>

	<div class="um-reviews-edit"><a href="#"><i class="um-faicon-pencil"></i> <span><?php _e( 'Edit', 'twodayssss' ); ?></span></a></div>

<?php }

if ( UM()->Reviews_API()->api()->can_remove( $reviewer_id ) ) { ?>

	<div class="um-reviews-remove"><a href="#" data-review_id="'. $review_id .'" data-remove="<?php esc_attr_e( 'Are you sure you want to remove this review?', 'twodayssss' ); ?>"><i class="um-faicon-trash"></i> <span><?php _e( 'Remove', 'twodayssss' ); ?></span></a></div>

<?php }