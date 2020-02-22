<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="'twodayssss'-profile-add-folder-holder">
	<div class="um-clear" style="text-align:center;">
		<br />
		<a href="javascript:void(0);" id="um-bookmarks-profile-add-folder" class="um-modal-btn alt">
			<?php printf( __( 'Add %s', 'twodayssss' ), $folder_text ); ?> <i class="um-faicon-angle-down icon"></i>
		</a>
	</div>
	<form id="'twodayssss'-profile-add-folder-form">
		<div class="um_bookmarks_table new-'twodayssss'-folder-tbl">
			<div class="um_bookmarks_tr">
				<div class="um_bookmarks_td">
					<input type="text" name="_um_user_bookmarks_folder" placeholder="<?php echo esc_attr( sprintf( __( '%s name', 'twodayssss' ), $folder_text ) ); ?>" required />
					<small class="error-message">
						<?php printf( __( '%s name is required.', 'twodayssss' ), $folder_text ); ?>
					</small>
				</div>
				<div class="um_bookmarks_td" style="vertical-align: middle;max-width:115px;">
					<input id="um_user_bookmarks_access_type_checkbox" type="checkbox" name="is_private" value="1" />
					<label for="um_user_bookmarks_access_type_checkbox"><?php _e( 'Private', 'twodayssss' ); ?></label>
				</div>
				<div class="um_bookmarks_td" style="max-width:115px">
					<button class="um_user_bookmarks_profile_create_folder_btn um-modal-btn" type="button" style="height:40px;">
						<?php _e('Create','twodayssss'); ?>
					</button>
				</div>
			</div>
		</div>

		<?php wp_nonce_field( 'um_user_bookmarks_new_bookmark_folder' ); ?>
		<input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>" />
		<input type="hidden" name="action" value="um_bookmarks_folder_add" />

		<div class="form-response" style="text-align:center;color:#ab1313;"></div>
	</form>
</div>