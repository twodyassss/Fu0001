<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend settings
 *
 * @param $settings
 *
 * @return mixed
 */
function um_user_tags_settings( $settings ) {

	$key = ! empty( $settings['extensions']['sections'] ) ? 'user_tags' : '';
	$settings['extensions']['sections'][ $key ] = array(
		'title'     => __( 'User Tags', 'twodayssss' ),
		'fields'    => array(
			array(
				'id'            => 'user_tags_max_num',
				'type'          => 'text',
				'label'         => __( 'Maximum number of tags to display in user profile','twodayssss' ),
				'validate'      => 'numeric',
				'descriptions'  => __('Remaining tags will appear by clicking on a link','twodayssss'),
				'size'          => 'small'
			),
		)
	);

	return $settings;
}
add_filter( 'um_settings_structure', 'um_user_tags_settings', 10, 1 );