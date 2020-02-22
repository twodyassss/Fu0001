<?php
/**
 * Template for the account options
 * Used on the "Account" page, "Notifications" tab
 * Called from the um_friends_account_tab() function
 *
 * This template can be overridden by copying it to yourtheme/ultimate-member/account-notifications.php.
 */
if( !defined( 'ABSPATH' ) ) exit;
?>

<div class="um-field-area">
	<label class="um-field-checkbox <?php if( !empty( $_enable_new_friend ) ) { ?>active<?php } ?>">
		<input type="checkbox" name="_enable_new_friend" value="1" <?php checked( !empty( $_enable_new_friend ) ) ?> />
		<span class="um-field-checkbox-state"><i class="um-icon-android-checkbox-<?php echo !empty( $_enable_new_friend ) ? 'outline' : 'outline-blank'; ?>"></i></span>
		<span class="um-field-checkbox-option"><?php _e( 'I have got a new friend', 'twodayssss' ); ?></span>
	</label>

	<div class="um-clear"></div>
</div>