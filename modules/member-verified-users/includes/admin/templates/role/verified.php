<div class="um-admin-metabox">
	<?php $role = $object['data'];

    UM()->admin_forms( array(
        'class'     => 'um-role-verified-users um-top-label um-half-column',
        'prefix_id' => 'role',
        'fields'    => array(
            array(
                'id'    => '_um_verified_by_role',
                'type'  => 'select',
                'label' => __( 'Users with this role will automatically have a verified account', 'twodayssss' ),
                'value' => ! empty( $role['_um_verified_by_role'] ) ? $role['_um_verified_by_role'] : 0,
                'options'		=> array(
                    0	=> __( 'No', 'twodayssss' ),
                    1	=> __( 'Yes', 'twodayssss' ),
                ),
            ),
            array(
                'id'    => '_um_verified_req_disallowed',
                'type'  => 'select',
                'label' => __('Prevent users with this role from requesting verification?', 'twodayssss' ),
                'value' => ! empty( $role['_um_verified_req_disallowed'] ) ? $role['_um_verified_req_disallowed'] : 0,
                'options'		=> array(
                    0	=> __( 'No', 'twodayssss' ),
                    1	=> __( 'Yes', 'twodayssss' ),
                ),
            ),
        )
    ) )->render_form(); ?>

    <div class="um-admin-clear"></div>
</div>