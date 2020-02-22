<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend settings
 *
 * @param $settings
 *
 * @return mixed
 */
function um_notices_settings( $settings ) {

	$key = ! empty( $settings['extensions']['sections'] ) ? 'notices' : '';

	$settings['extensions']['sections'][ $key ] = array(
		'title'     => __( 'Notices', 'twodayssss' ),
		'fields'    => array(
			array(
				'id'            => 'notice_pos',
				'type'          => 'select',
				'label'         => __( 'Notice Position in Footer', 'twodayssss' ),
				'options'       => array(
					'right' => __( 'Show to Right', 'twodayssss' ),
					'left'  => __( 'Show to Left', 'twodayssss' ),
				),
				'placeholder'   => __( 'Select...', 'twodayssss' ),
				'size'          => 'middle'
			)
		)
	);

	return $settings;
}
add_filter( 'um_settings_structure', 'um_notices_settings', 10, 1 );