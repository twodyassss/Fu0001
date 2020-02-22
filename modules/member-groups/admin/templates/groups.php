<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post; ?>

<div class="um-admin-metabox">

	<?php //$role = $object['data'];
          $id = get_the_ID();
	UM()->admin_forms( array(
		'class'		=> 'um-role-profile um-half-column',
		'prefix_id'	=> 'privacy_role',
		'fields' => array(
			array(
				'id'      => '_um_groups_privacy',
				'type'    => 'select',
				'label'   => __( 'Privacy', 'twodayssss' ),
				'options' => UM()->Groups()->api()->privacy_options,
				'value'   => ! empty( get_post_meta($id, '_um_groups_privacy',true)) ? get_post_meta($id, '_um_groups_privacy',true) : 'public',
			),
			array(
				'id'          => '_um_groups_privacy_roles',
				'type'        => 'select',
				'label'       => __( 'Role list', 'twodayssss' ),
				'options'     => UM()->roles()->get_roles(),
				'multi'       => true,
				'value'       => ! empty( get_post_meta($id, '_um_groups_privacy_roles',true) ) ? get_post_meta($id, '_um_groups_privacy_roles',true) : array(),
				'conditional' => array( '_um_groups_privacy', '=', 'public_role' )
			),
			array(
				'id'      => '_um_groups_can_invite',
				'type'    => 'select',
				'label'   => __( 'Who can invite members to the group?', 'twodayssss' ),
				'options' => UM()->Groups()->api()->can_invite,
				'value'   => ! empty( get_post_meta($id, '_um_groups_can_invite',true) ) ? get_post_meta($id, '_um_groups_can_invite',true) : 0,
			),
			array(
				'id'      => '_um_groups_posts_moderation',
				'type'    => 'select',
				'label'   => __( 'Posts Moderation', 'twodayssss' ),
				'options' => UM()->Groups()->api()->group_posts_moderation_options,
				'value'   => ! empty( get_post_meta($id, '_um_groups_posts_moderation',true) ) ? get_post_meta($id, '_um_groups_posts_moderation',true) : 0,
			),
		)
	) )->render_form(); ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'um_admin_save_metabox_groups_nonce' ); ?>


</div>