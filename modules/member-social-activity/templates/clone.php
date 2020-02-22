<?php global $user_ID; ?>
<?php um_fetch_user( $user_ID ); ?>

<script type="text/template" id="tmpl-um-activity-widget">
	<div class="um-activity-widget um-activity-clone unready" id="postid-{{{data.post_id}}}">
		<div class="um-activity-head">
			<div class="um-activity-left um-activity-author">
				<div class="um-activity-ava">
					<a href="<?php echo esc_attr( um_user_profile_url() ); ?>">
						<?php echo get_avatar( um_user( 'ID' ), 80 ); ?>
					</a>
				</div>
				<div class="um-activity-author-meta">
					<div class="um-activity-author-url">
						<a href="<?php echo esc_attr( um_user_profile_url() ); ?>" class="um-link">
							<?php echo um_user( 'display_name', 'html' ); ?>
						</a>
						<# if ( data.wall_id != data.user_id ) { #>
						<?php if ( ! empty( $wall_id ) && $wall_id != $user_ID ) {
							um_fetch_user( $wall_id ); ?>
							<i class="um-icon-forward"></i>
							<a href="<?php echo esc_attr( um_user_profile_url() ) ?>" class="um-link">
								<?php echo um_user( 'display_name' ) ?>
							</a>
						<?php um_fetch_user( $user_ID );
						} ?>
						<# } #>
					</div>
					<span class="um-activity-metadata">
						<a href="{{{data.post_url}}}"><?php _e( 'Just now', 'twodayssss' ); ?></a>
					</span>
				</div>
			</div>

			<div class="um-activity-right">

				<?php if ( is_user_logged_in() ) { ?>

					<a href="#" class="um-activity-ticon um-activity-start-dialog" data-role="um-activity-tool-dialog"><i class="um-faicon-chevron-down"></i></a>

					<div class="um-activity-dialog um-activity-tool-dialog">

						<a href="javascript:void(0);" class="um-activity-manage"><?php _e('Edit','twodayssss'); ?></a>

						<a href="javascript:void(0);" class="um-activity-trash" data-msg="<?php _e('Are you sure you want to delete this post?','twodayssss'); ?>"><?php _e('Delete','twodayssss'); ?></a>

					</div>

				<?php } ?>

			</div>

			<div class="um-clear"></div>

		</div>

		<div class="um-activity-body">

			<div class="um-activity-bodyinner <# if ( data.video ) { #>has-embeded-video<# } #><# if ( data.oembed ) { #> has-oembeded<# } #>">

				<div class="um-activity-bodyinner-edit">
					<textarea style="display:none!important">{{{data.content}}}</textarea>
					<input type="hidden" name="_photo" value="{{{data.img_src}}}" />
					<input type="hidden" name="_photo_url" value="{{{data.img_src_url}}}" />
				</div>

				<# if ( data.content.trim().length > 0 || data.link.trim().length > 0 ) { #>
					<div class="um-activity-bodyinner-txt">{{{data.content}}}{{{data.link}}}</div>
				<# } #>
				<# if ( data.photo ) { #>
				<div class="um-activity-bodyinner-photo">
					<a href="#" class="um-photo-modal" data-src="{{{data.modal}}}">
						<img src="{{{data.modal}}}" alt="" />
					</a>
				</div>
				<# } #>

				<div class="um-activity-bodyinner-video">
					{{{data.video_content}}}
				</div>
			</div>

		</div>

		<div class="um-activity-foot status" id="wallcomments-{{{data.post_id}}}">

			<div class="um-activity-left um-activity-actions">
				<div class="um-activity-like" data-like_text="<?php _e( 'Like','twodayssss' ); ?>" data-unlike_text="<?php _e( 'Unlike', 'twodayssss' ); ?>"><a href="#"><i class="um-faicon-thumbs-up"></i><span class=""><?php _e('Like','twodayssss'); ?></span></a></div>
				<?php if ( UM()->Activity_API()->api()->can_comment() ) { ?>
					<div class="um-activity-comment"><a href="javascript:void(0);"><i class="um-faicon-comment"></i><span class=""><?php _e('Comment','twodayssss'); ?></span></a></div>
				<?php } ?>
			</div>
			<div class="um-clear"></div>

		</div>

		<div class="um-activity-comments">

			<?php if ( is_user_logged_in() && UM()->Activity_API()->api()->can_comment() ) { //hidden comment area for clone ?>

				<div class="um-activity-commentl um-activity-comment-area">
					<div class="um-activity-comment-avatar">
						<?php echo get_avatar( get_current_user_id(), 80 ); ?>
					</div>
					<div class="um-activity-comment-box">
						<textarea class="um-activity-comment-textarea"
						          data-replytext="<?php esc_attr_e('Write a reply...','twodayssss'); ?>"
						          data-reply_to="0"
						          placeholder="<?php esc_attr_e('Write a comment...','twodayssss'); ?>"></textarea>
					</div>
					<div class="um-activity-right">
						<a href="javascript:void(0);" class="um-button um-activity-comment-post um-disabled">
							<?php _e( 'Comment', 'twodayssss' ); ?>
						</a>
					</div>

					<div class="um-clear"></div>
				</div>

			<?php } ?>

			<div class="um-activity-comments-loop"></div>

		</div>

	</div>
</script>


<script type="text/template" id="tmpl-um-activity-post">
	<div class="um-activity-bodyinner <# if ( data.video ) { #>has-embeded-video<# } #><# if ( data.oembed ) { #> has-oembeded<# } #>">
		<div class="um-activity-bodyinner-edit">
			<textarea style="display:none!important">{{{data.content}}}</textarea>
			<input type="hidden" name="_photo" value="{{{data.img_src}}}" />
			<input type="hidden" name="_photo_url" value="{{{data.img_src_url}}}" />
		</div>

		<# if ( data.content.trim().length > 0 || data.link.trim().length > 0 ) { #>
			<div class="um-activity-bodyinner-txt">{{{data.content}}}{{{data.link}}}</div>
		<# } #>

		<# if ( data.photo ) { #>
		<div class="um-activity-bodyinner-photo">
			<a href="#" class="um-photo-modal" data-src="{{{data.modal}}}">
				<img src="{{{data.modal}}}" alt="" />
			</a>
		</div>
		<# } #>

		<div class="um-activity-bodyinner-video">
			{{{data.video_content}}}
		</div>
	</div>
</script>


<script type="text/template" id="tmpl-um-activity-comment">
	<div class="um-activity-commentwrap" data-comment_id="{{{data.comment_id}}}">

		<div class="um-activity-commentl um-activity-commentl-clone unready" id="commentid-{{{data.comment_id}}}">

			<?php if ( is_user_logged_in() ) { ?>
				<# if ( ! data.user_hidden ) { #>
					<a href="javascript:void(0);" class="um-activity-comment-hide um-tip-s" title="<?php esc_attr_e('Hide','twodayssss'); ?>">
						<i class="um-icon-close-round"></i>
					</a>
				<# } #>
			<?php } ?>

			<div class="um-activity-comment-avatar hidden-{{{data.user_hidden}}}">
				<a href="<?php echo esc_attr( um_user_profile_url() ); ?>">
					<?php echo get_avatar( get_current_user_id(), 80 ); ?>
				</a>
			</div>

			<div class="um-activity-comment-hidden hidden-{{{data.user_hidden}}}">
				<?php _e('Comment hidden. <a href="javascript:void(0);" class="um-link">Show this comment</a>','twodayssss' ); ?>
			</div>

			<div class="um-activity-comment-info hidden-{{{data.user_hidden}}}">
				<div class="um-activity-comment-data">
					<span class="um-activity-comment-author-link">
						<a href="<?php echo esc_attr( um_user_profile_url() ); ?>" class="um-link">
							<?php echo um_user('display_name'); ?>
						</a>
					</span>
					<span class="um-activity-comment-text">{{{data.comment}}}</span>
					<textarea id="um-activity-reply-{{{data.comment_id}}}" class="original-content" style="display:none!important">{{{data.comment}}}</textarea>
				</div>
				<div class="um-activity-comment-meta">
					<?php if ( is_user_logged_in() ) { ?>
						<span>
							<a href="javascript:void(0);" class="um-link um-activity-comment-like"
							   data-like_text="<?php esc_attr_e( 'Like','twodayssss' ); ?>"
							   data-unlike_text="<?php esc_attr_e('Unlike','twodayssss'); ?>">
								<?php _e('Like','twodayssss'); ?>
							</a>
						</span>

						<?php if ( UM()->Activity_API()->api()->can_comment() ) { ?>
							<span>
								<a href="javascript:void(0);" class="um-link um-activity-comment-reply" data-commentid="{{{data.comment_id}}}">
									<?php _e('Reply','twodayssss'); ?>
								</a>
							</span>
						<?php } ?>

						<span>
							<a href="{{{data.permalink}}}" class="um-activity-comment-permalink">
								{{{data.time}}}
							</a>
						</span>

						<# if ( data.can_edit_comment ) { #>
						<span class="um-activity-editc">
							<a href="javascript:void(0);"><i class="um-icon-edit"></i></a>
							<span class="um-activity-editc-d">
								<a href="javascript:void(0);" class="edit" data-commentid="{{{data.comment_id}}}"><?php _e('Edit','twodayssss'); ?></a>
								<a href="javascript:void(0);" class="delete" data-msg="<?php _e('Are you sure you want to delete this comment?','twodayssss'); ?>">
									<?php _e('Delete','twodayssss'); ?>
								</a>
							</span>
						</span>
						<# } #>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</script>


<script type="text/template" id="tmpl-um-activity-comment-edit">
	<div class="um-activity-commentl um-activity-comment-area" style="padding-top:0;padding-left:0;">
		<div class="um-activity-comment-box">
			<textarea class="um-activity-comment-textarea" data-commentid="{{{data.comment_id}}}" data-reply_to="{{{data.reply_to}}}"
			          placeholder="<?php esc_attr_e('Write a comment...','twodayssss'); ?>">{{{data.comment}}}</textarea>
		</div>
		<div class="um-activity-right">
			<a href="javascript:void(0);" class="um-activity-comment-edit-cancel">
				<?php _e( 'Cancel editing', 'twodayssss' ); ?>
			</a>
			<a href="javascript:void(0);" class="um-button um-activity-comment-post um-disabled">
				<?php _e( 'Update', 'twodayssss' ); ?>
			</a>
		</div>

		<div class="um-clear"></div>
	</div>



	<div class="um-activity-commentwrap" data-comment_id="{{{data.comment_id}}}">

		<div class="um-activity-commentl um-activity-commentl-clone unready" id="commentid-{{{data.comment_id}}}">

			<?php if ( is_user_logged_in() ) { ?>
				<# if ( ! data.user_hidden ) { #>
					<a href="javascript:void(0);" class="um-activity-comment-hide um-tip-s" title="<?php esc_attr_e('Hide','twodayssss'); ?>">
						<i class="um-icon-close-round"></i>
					</a>
				<# } #>
			<?php } ?>

			<div class="um-activity-comment-avatar hidden-{{{data.user_hidden}}}">
				<a href="<?php echo esc_attr( um_user_profile_url() ); ?>">
					<?php echo get_avatar( get_current_user_id(), 80 ); ?>
				</a>
			</div>

			<div class="um-activity-comment-hidden hidden-{{{data.user_hidden}}}">
				<?php _e('Comment hidden. <a href="javascript:void(0);" class="um-link">Show this comment</a>','twodayssss' ); ?>
			</div>

			<div class="um-activity-comment-info hidden-{{{data.user_hidden}}}">
				<div class="um-activity-comment-data">
					<span class="um-activity-comment-author-link">
						<a href="<?php echo esc_attr( um_user_profile_url() ); ?>" class="um-link">
							<?php echo um_user('display_name'); ?>
						</a>
					</span>
					<span class="um-activity-comment-text">{{{data.comment}}}</span>
					<textarea id="um-activity-reply-{{{data.comment_id}}}" class="original-content" style="display:none!important">{{{data.comment}}}</textarea>
				</div>
				<div class="um-activity-comment-meta">
					<?php if ( is_user_logged_in() ) { ?>
						<span>
							<a href="javascript:void(0);" class="um-link um-activity-comment-like"
							   data-like_text="<?php esc_attr_e( 'Like','twodayssss' ); ?>"
							   data-unlike_text="<?php esc_attr_e('Unlike','twodayssss'); ?>">
								<?php _e('Like','twodayssss'); ?>
							</a>
						</span>

						<?php if ( UM()->Activity_API()->api()->can_comment() ) { ?>
							<span>
								<a href="javascript:void(0);" class="um-link um-activity-comment-reply" data-commentid="{{{data.comment_id}}}">
									<?php _e('Reply','twodayssss'); ?>
								</a>
							</span>
						<?php } ?>

						<span>
							<a href="{{{data.permalink}}}" class="um-activity-comment-permalink">
								{{{data.time}}}
							</a>
						</span>

						<# if ( data.can_edit_comment ) { #>
						<span class="um-activity-editc">
							<a href="javascript:void(0);"><i class="um-icon-edit"></i></a>
							<span class="um-activity-editc-d">
								<a href="javascript:void(0);" class="edit"><?php _e('Edit','twodayssss'); ?></a>
								<a href="javascript:void(0);" class="delete" data-msg="<?php _e('Are you sure you want to delete this comment?','twodayssss'); ?>">
									<?php _e('Delete','twodayssss'); ?>
								</a>
							</span>
						</span>
						<# } #>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</script>


<script type="text/template" id="tmpl-um-activity-reply">
	<div class="um-activity-commentl um-activity-comment-area">
		<div class="um-activity-comment-avatar">
			<?php echo get_avatar( get_current_user_id(), 80 ); ?>
		</div>
		<div class="um-activity-comment-box">
			<textarea class="um-activity-comment-textarea"
			          data-reply_to="{{{data.replyto}}}"
			          placeholder="<?php esc_attr_e( 'Write a reply...', 'twodayssss' ); ?>"></textarea>
		</div>
		<div class="um-activity-right">
			<a href="javascript:void(0);" class="um-button um-activity-comment-post um-disabled">
				<?php _e( 'Reply', 'twodayssss' ); ?>
			</a>
		</div>
		<div class="um-clear"></div>
	</div>
</script>


<script type="text/template" id="tmpl-um-activity-comment-reply">
	<div class="um-activity-commentl is-child" id="commentid-{{{data.comment_id}}}">

		<?php if ( is_user_logged_in() ) { ?>
			<# if ( ! data.user_hidden ) { #>
				<a href="javascript:void(0);" class="um-activity-comment-hide um-tip-s" title="<?php esc_attr_e('Hide','twodayssss'); ?>">
					<i class="um-icon-close-round"></i>
				</a>
			<# } #>
		<?php } ?>

		<div class="um-activity-comment-avatar hidden-{{{data.user_hidden}}}">
			<a href="<?php echo esc_attr( um_user_profile_url() ); ?>">
				<?php echo get_avatar( get_current_user_id(), 80 ); ?>
			</a>
		</div>

		<div class="um-activity-comment-hidden hidden-{{{data.user_hidden}}}">
			<?php _e('Reply hidden. <a href="javascript:void(0);" class="um-link">Show this reply</a>','twodayssss' ); ?>
		</div>

		<div class="um-activity-comment-info hidden-{{{data.user_hidden}}}">
			<div class="um-activity-comment-data">
				<span class="um-activity-comment-author-link"><a href="<?php echo um_user_profile_url(); ?>" class="um-link"><?php echo um_user('display_name'); ?></a></span> <span class="um-activity-comment-text">{{{data.comment}}}</span>
				<textarea id="um-activity-reply-{{{data.comment_id}}}" class="original-content" style="display:none!important">{{{data.comment}}}</textarea>
			</div>
			<div class="um-activity-comment-meta">
				<?php if ( is_user_logged_in() ) { ?>
					<span>
						<# if ( data.user_liked_comment ) { #>
							<a href="javascript:void(0);" class="um-link um-activity-comment-like active" data-like_text="<?php _e('Like','twodayssss'); ?>" data-unlike_text="<?php _e('Unlike','twodayssss'); ?>">
								<?php _e('Unlike','twodayssss' ); ?>
							</a>
						<# } else { #>
							<a href="javascript:void(0);" class="um-link um-activity-comment-like" data-like_text="<?php _e('Like','twodayssss'); ?>" data-unlike_text="<?php _e('Unlike','twodayssss'); ?>">
								<?php _e('Like','twodayssss' ); ?>
							</a>
						<# } #>
					</span>
					<span class="um-activity-comment-likes count-{{{data.likes}}}">
						<a href="#">
							<i class="um-faicon-thumbs-up"></i>
							<ins class="um-activity-ajaxdata-commentlikes">{{{data.likes}}}</ins>
						</a>
					</span>
				<?php } ?>
				<span>
					<a href="{{{data.permalink}}}" class="um-activity-comment-permalink">
						{{{data.time}}}
					</a>
				</span>

				<# if ( data.can_edit_comment ) { #>
					<span class="um-activity-editc"><a href="javascript:void(0);"><i class="um-icon-edit"></i></a>
						<span class="um-activity-editc-d">
							<a href="javascript:void(0);" class="edit" data-commentid="{{{data.comment_id}}}"><?php _e('Edit','twodayssss'); ?></a>
							<a href="javascript:void(0);" class="delete" data-msg="<?php _e('Are you sure you want to delete this comment?','twodayssss'); ?>">
								<?php _e('Delete','twodayssss'); ?>
							</a>
						</span>
					</span>
				<# } #>
			</div>
		</div>
	</div>
</script>