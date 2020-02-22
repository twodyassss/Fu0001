<?php
/**
 * Template for the UM User Reviews, my reviews
 *
 * Page: "Profile", tab "Reviews"
 * Caller: um_profile_content_reviews_default() function
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/um-reviews/review-my.php
 */
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>

<!-- um-reviews/templates/review-my.php -->
<div class="um-reviews-none">

	<?php echo ( um_is_myprofile() ) ? __( 'You have not received any reviews yet.', 'twodayssss' ) : __( 'This user did not receive any other reviews.', 'twodayssss' ); ?>

</div>