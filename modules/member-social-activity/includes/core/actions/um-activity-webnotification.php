<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@Adds a notification type
	***/
	add_filter('um_notifications_core_log_types', 'um_activity_add_notification_type', 200 );
	function um_activity_add_notification_type( $array ) {
		
		$array['new_wall_post'] = array(
			'title' => __('User get a new wall post','twodayssss'),
			'template' => '<strong>{member}</strong> has posted on your wall.',
			'account_desc' => __('When someone publish a post on my wall','twodayssss'),
		);
		
		$array['new_wall_comment'] = array(
			'title' => __('User get a new wall comment','twodayssss'),
			'template' => '<strong>{member}</strong> has commented on your wall post.',
			'account_desc' => __('When someone comments on your post','twodayssss'),
		);
		
		$array['new_post_like'] = array(
			'title' => __('User get a new post like','twodayssss'),
			'template' => '<strong>{member}</strong> likes your wall post.',
			'account_desc' => __('When someone likes your post','twodayssss'),
		);
		
		$array['new_mention'] = array(
			'title' => __('User get a new mention','twodayssss'),
			'template' => '<strong>{member}</strong> just mentioned you.',
			'account_desc' => __('When someone mentions me','twodayssss'),
		);
		
		return $array;
	}
	
	/***
	***	@Adds a notification icon
	***/
	add_filter('um_notifications_get_icon', 'um_activity_add_notification_icon', 10, 2 );
	function um_activity_add_notification_icon( $output, $type ) {
		
		if ( $type == 'new_wall_post' ) {
			$output = '<i class="um-icon-compose" style="color: #3ba1da"></i>';
		}
		
		if ( $type == 'new_wall_comment' ) {
			$output = '<i class="um-icon-chatbox" style="color: #00b56c"></i>';
		}
		
		if ( $type == 'new_post_like' ) {
			$output = '<i class="um-faicon-thumbs-up" style="color: #1c6dc9"></i>';
		}
		
		if ( $type == 'new_mention' ) {
			$output = '<i class="um-icon-ios-contact" style="color: #00c9ae"></i>';
		}

		if ( $type == 'comment_reply' ) {
			$output = '<i class="um-icon-chatboxes" style="color: #00b56c"></i>';
		}
		
		return $output;
	}