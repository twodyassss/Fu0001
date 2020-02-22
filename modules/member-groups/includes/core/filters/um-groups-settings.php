<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Add Groups global settings
 *
 * @param $settings
 *
 * @return mixed
 */
function um_groups_config( $settings ) {

	$key = ! empty( $settings['extensions']['sections'] ) ? 'groups' : '';

	$array_invite_people_opts = array(
		'everyone' => __( 'Everyone', 'twodayssss' ),
	);
	$array_invite_people_opts = apply_filters( 'um_groups_invite_people', $array_invite_people_opts );

	$settings['extensions']['sections'][ $key ] = array(
		'title'     => __( 'Groups', 'twodayssss' ),
		'fields'    => array(
			array(
				'id'    => 'groups_show_avatars',
				'type'  => 'checkbox',
				'label' => __( 'Show group avatars', 'twodayssss' ),
			),
			array(
				'id'            => 'groups_invite_people',
				'type'          => 'select',
				'label'         => __( 'Show people to Invite tab', 'twodayssss' ),
				'options'       => $array_invite_people_opts,
				'placeholder'   => __( 'Select...', 'twodayssss' ),
				'size'          => 'small'
			),
			array(
				'id'    => 'groups_posts_num',
				'type'  => 'text',
				'label' => __( 'Number of discussion posts on desktop', 'twodayssss' ),
				'size'  => 'small'
			),
			array(
				'id'    => 'groups_posts_num_mob',
				'type'  => 'text',
				'label' => __( 'Number of discussion posts on mobile', 'twodayssss' ),
				'size'  => 'small'
			),
			array(
				'id'    => 'groups_init_comments_count',
				'type'  => 'text',
				'label' => __( 'Number of initial comments/replies to display per post', 'twodayssss' ),
				'size'  => 'small'
			),
			array(
				'id'    => 'groups_load_comments_count',
				'type'  => 'text',
				'label' => __( 'Number of comments/replies to get when user load more', 'twodayssss' ),
				'size'  => 'small'
			),
			array(
				'id'            => 'groups_order_comment',
				'type'          => 'select',
				'label'         => __( 'Comments order', 'twodayssss' ),
				'options'       => array(
					'desc'  => __( 'Newest first', 'twodayssss' ),
					'asc'   => __( 'Oldest first', 'twodayssss' ),
				),
				'placeholder'   => __( 'Select...', 'twodayssss' ),
				'size'          => 'small'
			),
			array(
				'id'    => 'groups_post_truncate',
				'type'  => 'text',
				'label' => __( 'How many words appear before discussion post is truncated?', 'twodayssss' ),
				'size'  => 'small'

			),
			array(
				'id'    => 'groups_need_to_login',
				'type'  => 'textarea',
				'label' => __( 'Text to display If user needs to login to interact in a post', 'twodayssss' ),
				'rows'  => 2,
			),
		)
	);

	return $settings;
}
add_filter( "um_settings_structure", 'um_groups_config', 10, 1 );


/**
 * Add Groups core pages
 *
 * @param $pages
 *
 * @return mixed
 */
function um_groups_core_pages( $pages ) {
	$pages['create_group'] = array(
		'title' => __( 'Create Group', 'twodayssss' )
	);

	$pages['my_groups'] = array(
		'title' => __( 'My Groups', 'twodayssss' )
	);

	$pages['groups'] = array(
		'title' => __( 'Groups', 'twodayssss' )
	);

	$pages['group_invites'] = array(
		'title' => __( 'Invites', 'twodayssss' )
	);

	return $pages;
}
add_filter( 'um_core_pages', 'um_groups_core_pages', 10, 1 );


/**
 * Email notifications templates
 *
 * @param array $notifications
 *
 * @return array
 */
function um_groups_email_notifications( $notifications ) {

	$notifications['groups_approve_member'] = array(
		'key'           	=> 'groups_approve_member',
		'title'        		=> __( 'Groups - Approve Member Email','twodayssss' ),
		'subject'       	=> '{site_name} - Your request to join {group_name} has been approved.',
		'body'         		=> 'Your request to join {group_name} has been approved.<br /><br />' .
		                         '{group_url}',
		'description'   	=> __('Whether to send the user an email when user is approved to a group','ultimate-member'),
		'recipient'   		=> 'user',
		'default_active' 	=> true
	);

	$notifications['groups_join_request'] = array(
		'key'           	=> 'groups_join_request',
		'title'         	=> __( 'Groups - Join Request Email','twodayssss' ),
		'subject'       	=> '{site_name} - Join Request',
		'body'          	=> 'Hi {moderator_name},<br/><br/>' .
		                     '{member_name} has requested to join {group_name}. You can view their profile here: {profile_link}.' .
		                     'To approve/reject this request please click the following link: {groups_request_tab_url}<br /><br />',
		'description'   	=> __('Whether to send the user an email when user has requested to join their group','ultimate-member'),
		'recipient'   		=> 'user',
		'default_active' 	=> true
	);

	$notifications['groups_invite_member'] = array(
		'key'           => 'groups_invite_member',
		'title'         => __( 'Groups - Invite Member Email','twodayssss' ),
		'subject'       => '{site_name} - You have been invited to join {group_name}',
		'body'          => 'Hi {group_invitation_guest_name},<br /><br />'.
		                   '{group_invitation_host_name} has invited you to join {group_name}.<br /><br />'.
		                   'To confirm/reject this invitation please click the following link: {group_url}',
		'description'   => __('Whether to send the user an email when user has invited to join a group','ultimate-member'),
		'recipient'   => 'user',
		'default_active' => true
	);
	
	$notifications['groups_new_post'] = array(
		'key'           	=> 'groups_new_post',
		'title'         	=> __( 'Groups - New post','twodayssss' ),
		'subject'       	=> '{site_name} - {author_name} added a new post on group "{group_name}"',
		'body'          	=> 'Hi {group_invitation_guest_name},<br /><br />'.
		                   '{group_invitation_host_name} has posted new post on {group_name}.<br /><br />'.
		                   'To view post, please click the following link: {group_url}',
		'description'   	=> __('Whether to send the user an email when someone posts on group.','ultimate-member'),
		'recipient'   	 	=> 'user',
		'default_active' 	=> true
	);

	$notifications['groups_new_comment'] = array(
		'key'           	=> 'groups_new_comment',
		'title'         	=> __( 'Groups - New comment','twodayssss' ),
		'subject'       	=> '{site_name} - {author_name} added a new comment on post on group "{group_name}"',
		'body'          	=> 'Hi {group_invitation_guest_name},<br /><br />'.
		                   '{group_invitation_host_name} has commented on {group_name}.<br /><br />'.
		                   'To view comment, please click the following link: {group_url}',
		'description'   	=> __('Whether to send the user an email when someone posts comment on group.','ultimate-member'),
		'recipient'   	 	=> 'user',
		'default_active' 	=> true
	);

	return $notifications;
}
add_filter( 'um_email_notifications', 'um_groups_email_notifications', 10, 1 );