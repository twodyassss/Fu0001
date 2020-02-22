<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>


<a href="javascript:void(0);" class="'twodayssss'-folder" data-profile="<?php echo $profile_id; ?>"
   data-nonce="<?php echo wp_create_nonce( 'um_user_bookmarks_folder_' . $key ); ?>" data-folder_key="<?php echo $key; ?>">

	<div class="'twodayssss'-folder-container">
		<?php twodays_get_template( 'profile/folder-view/folder/title.php', um_user_bookmarks_plugin, array(
			'title' => $folder['title'],
		), true );

		twodays_get_template( 'profile/folder-view/folder/folder-info.php', um_user_bookmarks_plugin, array(
			'count'         => $count,
			'text'          => __( 'saved', 'twodayssss' ),
			'access_type'   => ucfirst( $folder['type'] ),
		), true ); ?>
	</div>
</a>