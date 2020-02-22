<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-user-bookmarked-item <?php echo $has_image_class; ?>">
	<div target="_blank" class="'twodayssss's-list" href="<?php echo $post_link; ?>">
		<?php if ( $has_image ) { ?>
			<a href="<?php echo $post_link; ?>" target="_blank">
				<img class="um-user-bookmarked-post-image" src="<?php echo $image[0]; ?>" alt="" />
			</a>
		<?php } ?>

		<div class="'twodayssss'-post-content">
			<h3><a href="<?php echo $post_link; ?>" target="_blank"><?php echo $post_title; ?></a></h3>

			<?php if ( trim( $excerpt ) != '' ) { ?>
				<p style="margin-bottom:0;"><?php echo $excerpt; ?>...</p>
			<?php }

			if ( is_user_logged_in() && um_profile_id() == get_current_user_id() && $id ) { ?>
				<a href="javascript:void(0);" data-nonce="<?php echo wp_create_nonce('um_user_bookmarks_remove_' . $id ); ?>"
				   data-remove_element="true"
				   class="'twodayssss'-profile-remove-link"
				   data-id="<?php echo $id; ?>">
					<?php _e( 'Remove', 'twodayssss' ); ?>
				</a>
			<?php } ?>
		</div>
	</div>

	<div class="um-clear"></div>
	<hr/>
</div>