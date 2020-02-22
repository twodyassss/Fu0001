<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend myCRED settings
 *
 * @param array $settings
 *
 * @return array
 */
function um_social_login_mycred_settings_award( $settings ) {
	$networks = UM()->Social_Login_API()->networks;
	foreach ( $networks as $id => $arr ) {
		$settings[ $id ] = sprintf( __( 'user connects with %s', 'twodayssss' ), $arr['name'] );
	}
	return $settings;
}
add_filter( 'um_mycred_extend_award_settings', 'um_social_login_mycred_settings_award', 10, 1 );


/**
 * Extend myCRED settings
 *
 * @param $settings
 *
 * @return mixed
 */
function um_social_login_mycred_settings_deduct( $settings ) {
	$networks = UM()->Social_Login_API()->networks;
	foreach ( $networks as $id => $arr ) {
		$settings[ $id ] = sprintf( __( 'user disconnects from %s', 'twodayssss' ), $arr['name'] );
	}
	return $settings;
}
add_filter( 'um_mycred_extend_deduct_settings', 'um_social_login_mycred_settings_deduct', 10, 1);


/**
 * Extend settings
 *
 * @param $settings
 *
 * @return mixed
 */
function um_social_login_settings( $settings ) {

	UM()->Social_Login_API()->init_networks();
	$networks = UM()->Social_Login_API()->networks;

	$key = ! empty( $settings['extensions']['sections'] ) ? 'social-login' : '';
	$settings['extensions']['sections'][ $key ] = array(
		'title'     => __( 'Social Login','twodayssss'),
		'fields'    => array(
			array(
				'id'        => 'account_tab_social',
				'type'      => 'checkbox',
				'label'     => __( 'Social Account Tab', 'twodayssss' ),
				'tooltip'   => __( 'Enable/disable the Social account tab in account page', 'twodayssss' ),
			),
			array(
				'id'        => 'register_show_social',
				'type'      => 'checkbox',
				'label'     => __( 'Show social connect on registration forms', 'twodayssss' ),
				'tooltip'   => __( 'Show/hide social connect on all registration forms by default', 'twodayssss'),
			),
			array(
				'id'        => 'login_show_social',
				'type'      => 'checkbox',
				'label'     => __( 'Show social connect on login forms','twodayssss' ),
				'tooltip'   => __( 'Show/hide social connect on all login forms by default', 'twodayssss' ),
			)
		)
	);

	$i = 0;
	foreach( $networks as $id => $arr ) {
		$i++;
		$sort[ $i ] = $id;
	}

	foreach ( $networks as $network_id => $array ) {
		$options = array();

		$options[] = array(
			'id'    => 'enable_' . $network_id,
			'type'  => 'checkbox',
			'label' => sprintf( __( '%s Social Connect', 'twodayssss' ), $array['name'] ),
		);

		if ( isset( $array['opts'] ) ) {
			foreach ( $array['opts'] as $opt_id => $title ) {
				$options[] = array(
					'id'            => $opt_id,
					'type'          => 'text',
					'label'         => $title,
					'conditional'   => array( "enable_$network_id", '=', '1' ),
				);
			}
		}

		$settings['extensions']['sections'][ $key ]['fields'] = array_merge( $settings['extensions']['sections'][ $key ]['fields'], $options );
	}

	return $settings;
}
add_filter( 'um_settings_structure', 'um_social_login_settings', 10, 1 );