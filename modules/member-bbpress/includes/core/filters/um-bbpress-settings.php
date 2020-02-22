<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend settings
 *
 * @param $settings
 *
 * @return mixed
 */
function um_bbpress_settings( $settings ) {
	$settings['licenses']['fields'][] = array(
		'id'        => 'um_bbpress_license_key',
		'label'     => __( 'bbPress License Key', 'twodayssss' ),
		'item_name' => 'bbPress',
		'author'    => 'Ultimate Member',
		'version'   => um_bbpress_version,
	);

	return $settings;
}
add_filter( 'um_settings_structure', 'um_bbpress_settings', 10, 1 );


/**
 * @param $settings
 * @param $key
 *
 * @return mixed
 */
function um_bbpress_mycred_settings_extend( $settings, $key ) {
	$settings['extensions']['sections'][$key]['fields'] = array_merge( $settings['extensions']['sections'][$key]['fields'], array(
		$fields[] = array(
			'id'    => 'mycred_hide_role',
			'type'  => 'checkbox',
			'label' => __( 'Hide bbPress Role?','twodayssss' ),
		),
		array(
			'id'    => 'mycred_show_bb_rank',
			'type'  => 'checkbox',
			'label' => __( 'Show user rank in bbPress replies', 'twodayssss' ),
		),
		array(
			'id'    => 'mycred_show_bb_points',
			'type'  => 'checkbox',
			'label' => __( 'Show user balance in bbPress replies', 'twodayssss' ),
		),
		array(
			'id'    => 'mycred_show_bb_progress',
			'type'  => 'checkbox',
			'label' => __( 'Show user progress in bbPress replies', 'twodayssss' ),
		)
	) );

	return $settings;
}
add_filter( 'um_mycred_settings_extend', 'um_bbpress_mycred_settings_extend', 10, 2 );