<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-groups-comments">

	<?php if ( is_user_logged_in() && UM()->Groups()->discussion()->can_comment() ) { ?>

	<div class="um-groups-commentl um-groups-comment-area">
		<div class="um-groups-comment-avatar"><?php echo get_avatar( get_current_user_id(), 80 ); ?></div>
		<div class="um-groups-comment-box"><textarea class="um-groups-comment-textarea" data-replytext="<?php _e('Write a reply...','twodayssss'); ?>" data-reply_to="0" placeholder="<?php _e('Write a comment...','twodayssss'); ?>"></textarea></div>
	</div>

	<?php } ?>

	<div class="um-groups-comments-loop">

		<div class="um-groups-commentl um-groups-commentl-clone">
			<a href="#" class="um-groups-comment-hide um-tip-s" title="<?php _e('Hide','twodayssss'); ?>">
				<i class="um-icon-close-round"></i>
			</a>
			<div class="um-groups-comment-avatar">

					<a href="<?php echo um_user_profile_url(); ?>"><?php echo get_avatar( get_current_user_id(), 80 ); ?></a>
			</div>
			<div class="um-groups-comment-info">
				<div class="um-groups-comment-data">
					<span class="um-groups-comment-author-link"><a href="<?php echo um_user_profile_url(); ?>" class="um-link"><?php echo um_user('display_name'); ?></a></span> <span class="um-groups-comment-text"></span>
					<textarea id="um-groups-reply-0" class="original-content" style="display:none!important"><?php
					if( isset( $comment->comment_content ) ){
					 echo $comment->comment_content;
					} ?></textarea>
				</div>
				<div class="um-groups-comment-meta">
					<?php if ( is_user_logged_in() ) { ?>
					<span><a href="#" class="um-link um-groups-comment-like" data-like_text="<?php _e('Like','twodayssss'); ?>" data-unlike_text="<?php _e('Unlike','twodayssss'); ?>"><?php _e('Like','twodayssss'); ?></a></span>
					<?php if ( UM()->Groups()->discussion()->can_comment() ) { ?><span><a href="#" class="um-link um-groups-comment-reply" data-commentid="0"><?php _e('Reply','twodayssss'); ?></a></span><?php } ?>

					<span class="um-groups-editc"><a href="#"><i class="um-icon-edit"></i></a>
						<span class="um-groups-editc-d">
							<a href="#" class="edit"><?php _e('Edit','twodayssss'); ?></a>
							<a href="#" class="delete" data-msg="<?php _e('Are you sure you want to delete this comment?','twodayssss'); ?>"><?php _e('Delete','twodayssss'); ?></a>
						</span>
					</span>

					<?php } ?>
				</div>
			</div>
		</div>
		<div class="um-groups-commentwrap-clone" data-comment_id="">
			<div class="um-groups-comment-child"></div>
		</div>
		
		<div class="um-groups-commentl is-child um-groups-commentlre-clone">
			<a href="#" class="um-groups-comment-hide um-tip-s" title="<?php _e('Hide','twodayssss'); ?>"><i class="um-icon-close-round"></i></a>
			<div class="um-groups-comment-avatar"><a href="<?php echo um_user_profile_url(); ?>"><?php echo get_avatar( get_current_user_id(), 80 ); ?></a></div>
			<div class="um-groups-comment-info">
				<div class="um-groups-comment-data">
					<span class="um-groups-comment-author-link"><a href="<?php echo um_user_profile_url(); ?>" class="um-link"><?php echo um_user('display_name'); ?></a></span> <span class="um-groups-comment-text"></span>
					<textarea id="um-groups-reply-0" class="original-content" style="display:none!important"><?php
					if( isset( $comment->comment_content ) ){
					 echo $comment->comment_content;
					} ?></textarea>
				</div>
				<div class="um-groups-comment-meta">
					<?php if ( is_user_logged_in() ) { ?>
					<span><a href="#" class="um-link um-groups-comment-like" data-like_text="<?php _e('Like','twodayssss'); ?>" data-unlike_text="<?php _e('Unlike','twodayssss'); ?>"><?php _e('Like','twodayssss'); ?></a></span>

					<span class="um-groups-editc"><a href="#"><i class="um-icon-edit"></i></a>
						<span class="um-groups-editc-d">
						<?php if( isset( $comment->comment_ID ) ){ ?>
							<a href="#" class="edit" data-commentid="<?php echo $comment->comment_ID; ?>"><?php _e('Edit','twodayssss'); ?></a>
						<?php } ?>
							<a href="#" class="delete" data-msg="<?php _e('Are you sure you want to delete this comment?','twodayssss'); ?>"><?php _e('Delete','twodayssss'); ?></a>
						</span>
					</span>

					<?php } ?>
				</div>
			</div>
		</div>

		<?php
		// Comments display
		if ( $post_id > 0 ) {
		$comments_all = UM()->Groups()->discussion()->get_comments_number( $post_id );
		if ( $comments_all > 0 ) {

			$comm_num = ( isset( $_GET['wall_comment_id'] ) && absint( $_GET['wall_comment_id'] ) ) ? 10000 : UM()->options()->get('activity_init_comments_count');

			$comments = get_comments( array( 'post_id' => $post_id, 'parent' => 0, 'number' => $comm_num, 'offset' => 0, 'order' => UM()->options()->get('activity_order_comment') ) );

			include um_groups_path . 'templates/discussion/comment.php';

			// Do we have more comments
			if ( $comments_all > count( $comments ) ) {
				$calc = $comments_all - count( $comments );
				if ( $calc > 1 ) {
					$text = sprintf(__('load %s more comments','twodayssss'), $calc );
				} else if ( $calc == 1 ) {
					$text = sprintf(__('load %s more comment','twodayssss'), $calc );
				}
				echo '<a href="#" class="um-groups-commentload" data-load_replies="'. __('load more replies','twodayssss').'" data-load_comments="'.__('load more comments','twodayssss') . '" data-loaded="'. count( $comments ) . '"><i class="um-icon-forward"></i><span>' . $text . '</span></a>';
				echo '<div class="um-groups-commentload-spin"></div>';
			}

		}
		}
		?>

	</div>

</div>
