<?php
/**
 * Template for the UM Groups Invites users search
 * Used on the "Create New Group" page
 * Called from the Friends_Shortcode->ultimatemember_friends_bar() method
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/friends-bar.php.
 */
if( !defined( 'ABSPATH' ) ) {
	exit;
}
$class = ( isset( $_REQUEST[ 'profiletab' ] ) && $_REQUEST[ 'profiletab' ] == 'friends' ) ? 'current' : '';
?>
<div class="um-friends-bar">

	<div class="um-friends-rc">
		<?php if( $can_view ) { ?>
			<a href="<?php echo UM()->Friends_API()->api()->friends_link( $user_id ); ?>" class="<?php echo esc_attr( $class ); ?>"><?php _e( 'friends', 'twodayssss' ); ?><?php echo UM()->Friends_API()->api()->count_friends( $user_id ); ?></a>
		<?php } ?>
	</div>

	<?php if( UM()->Friends_API()->api()->can_friend( $user_id, get_current_user_id() ) ) { ?>
		<div class="um-friends-btn">
			<?php echo UM()->Friends_API()->api()->friend_button( $user_id, get_current_user_id() ); ?>
			<?php do_action( 'um_after_friend_button_profile', $user_id ); ?>
		</div>
	<?php } ?>
	<div class="um-clear"></div>
</div>