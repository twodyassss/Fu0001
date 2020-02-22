<?php if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Extend UM settings
 *
 * @param $settings
 * @return mixed
 */
function um_private_content_settings( $settings ) {
    
    $key = ! empty( $settings['extensions']['sections'] ) ? 'private-content' : '';
    $settings['extensions']['sections'][$key] = array(
        'title'     => __( 'Private Content', 'twodayssss' ),
        'fields'    => array(
            array(
                'id'       => 'private_content_generate',
                'type'     => 'ajax_button',
                'label'    => __( 'Generate pages', 'twodayssss' ),
                'value'    => __( 'Generate pages for existing users', 'twodayssss' ),
                'tooltip'  => __( 'Generate pages for already existing users', 'twodayssss' ),
                'size'     => 'small'
            ),
            array(
                'id'        => 'tab_private_content_title',
                'type'      => 'text',
                'label'     => __( 'Private Content Tab Title','twodayssss' ),
                'tooltip'   => __( 'This is the title of the tab for show user\'s private content', 'twodayssss' ),
            ),
            array(
                'id'            => 'tab_private_content_icon',
                'type'          => 'text',
                'title'         => __( 'Private Content Tab Icon','twodayssss' ),
                'tooltip' 	    => __( 'This is the icon of the tab for show user\'s private content','twodayssss' ),
                'class'         => 'private_content_icon',
            )
        )
    );

    return $settings;
}
add_filter( 'um_settings_structure', 'um_private_content_settings', 10, 1 );