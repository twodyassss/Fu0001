<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<a style="width:5%;float:right;text-align:center;display:inline-block;" href="javascript:void(0);" class="um-profile-edit-folder-a">
	<i class="um-faicon-cog"></i>
</a>

<div class="um-clear"></div>

<div class="um-dropdown 'twodayssss'-dropdown" style="top:30px; width: 200px; right:0; left: auto; text-align: center; display: none;">
	<div class="um-dropdown-b">
		<div class="um-dropdown-arr" style="top:-17px;right:0;left: auto;">
			<i class="um-icon-arrow-up-b"></i>
		</div>
		<ul>
			<li>
				<a href="javascript:void(0);" class="'twodayssss'-folder-edit" data-folder_key="<?php echo $key; ?>"
				   data-profile="<?php echo $user_id ?>"
				   data-nonce="<?php echo wp_create_nonce( 'um_user_bookmarks_folder_edit' ); ?>">
					<?php _e( 'Edit', 'twodayssss' ) ?>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" class="'twodayssss'-folder-delete" data-folder_key="<?php echo $key; ?>"
				   data-alert_text="<?php echo __( 'Delete folder and its content?', 'twodayssss' ); ?>"
				   data-profile="<?php echo $user_id ?>" data-nonce="<?php echo wp_create_nonce( 'um_user_bookmarks_folder_delete' ); ?>"
				   data-callback-nonce="<?php echo wp_create_nonce( 'um_user_bookmarks_back' ); ?>">
					<?php _e( 'Delete', 'twodayssss' ) ?>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" class="'twodayssss'-dropdown-hide">
					<?php _e( 'Cancel', 'twodayssss' ); ?>
				</a>
			</li>
		</ul>
	</div>
</div>