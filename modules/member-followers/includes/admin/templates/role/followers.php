<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<?php $role = $object['data'];

	UM()->admin_forms( array(
		'class'     => 'um-role-followers um-half-column',
		'prefix_id' => 'role',
		'fields'    => array(
			array(
				'id'        => '_um_can_follow',
				'type'      => 'checkbox',
				'label'     => __( 'Can follow others?','twodayssss' ),
				'tooltip'   => __( 'Can this role follow other members or not.','twodayssss' ),
				'value'     => isset( $role['_um_can_follow'] ) ? $role['_um_can_follow'] : 1,
			),
			array(
				'id'            => '_um_can_follow_roles',
				'type'          => 'select',
				'multi'         => true,
				'label'         => __( 'Can follow these user roles only','twodayssss' ),
				'value'         => ! empty( $role['_um_can_follow_roles'] ) ? $role['_um_can_follow_roles'] : array(),
				'options'       =>  UM()->roles()->get_roles(),
				'conditional'   => array( '_um_can_follow', '=', '1' )
			)
		)
	) )->render_form(); ?>

	<div class="um-admin-clear"></div>
</div>