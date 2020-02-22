<?php
/**
 * Template for the UM User Reviews, no reviews
 *
 * Page: "Profile", tab "Reviews"
 * Caller: um_profile_content_reviews_default() function
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-reviews/review-none.php
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<!-- um-reviews/templates/review-none.php -->
<div class="um-reviews-none">

	<?php echo ( um_is_myprofile() ) ? __('You have not received any reviews yet.','twodayssss') : __('This user did not receive any reviews yet.','twodayssss'); ?>

</div>