<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * @param $array
 *
 * @return mixed
 */
function um_groups_notifications_core_log_types( $array ){


	$array['groups_approve_member'] = array(
		'title' => __('Groups - Approve Member','twodayssss'),
		'template' => __('Your request to join {group_name} have been approved.','twodayssss'),
		'account_desc' => __('When my group requests have been approved','twodayssss'),
	);


	$array['groups_join_request'] = array(
		'title' => __('Groups - Join Request','twodayssss'),
		'template' => __('{member_name} has requested to join {group_name}.','twodayssss'),
		'account_desc' => __('When a user requested to join their group','twodayssss'),
	);


	$array['groups_invite_member'] = array(
		'title' => __('Groups - Invite Member','twodayssss'),
		'template' => __('{group_invitation_host_name} has invited you to join {group_name}.','twodayssss'),
		'account_desc' => __('When a member has invited to join a group','twodayssss'),
	);

	$array['groups_change_role'] = array(
		'title' => __('Groups - Change Group Role','twodayssss'),
		'template' => __('Your group role {group_role_old} has been changed to {group_role_new} in {group_name}.','twodayssss'),
		'account_desc' => __('When my group roles have been changed','twodayssss'),
	);

	return $array;
}
add_filter("um_notifications_core_log_types","um_groups_notifications_core_log_types", 200, 1 );


/**
 * Add notification icon
 *
 * @param $output
 * @param $type
 *
 * @return string
 */
function um_groups_add_notification_icon( $output, $type ) {
	if ( in_array( $type, array( 'groups_approve_member' , 'groups_join_request', 'groups_invite_member', 'groups_change_role' ) ) ) {
		$output = '<i class="um-faicon-users" style="color: #3ba1da"></i>';
	}

	return $output;
}
add_filter('um_notifications_get_icon', 'um_groups_add_notification_icon', 10, 2 );