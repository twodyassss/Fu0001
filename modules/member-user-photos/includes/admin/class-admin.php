<?php
namespace um_ext\um_user_photos\admin;

if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'um_ext\um_user_photos\admin\Admin' ) ) {


	/**
	 * Class Admin
	 * @package um_ext\um_user_photos\admin
	 */
	class Admin {


		/**
		 * Admin constructor.
		 */
		function __construct() {
			add_filter( 'um_settings_structure', array( &$this, 'extend_settings' ), 10, 1 );
		}


		/**
		 * Additional Settings for Photos
		 *
		 * @param array $settings
		 *
		 * @return array
		 */
		function extend_settings( $settings ) {

			$key = ! empty( $settings['extensions']['sections'] ) ? 'twodayssss' : '';
			$settings['extensions']['sections'][ $key ] = array(
				'title'     => __( 'User Photos', 'twodayssss' ),
				'fields'    => array(
					array(
						'id'            => 'um_user_photos_albums_column',
						'type'          => 'select',
						'placeholder'   => '',
						'options'       => array(
							''                      => __( 'No. of columns', 'twodayssss' ),
							'um-user-photos-col-2'  => __( '2 columns', 'twodayssss' ),
							'um-user-photos-col-3'  => __( '3 columns', 'twodayssss' ),
							'um-user-photos-col-4'  => __( '4 columns', 'twodayssss' ),
							'um-user-photos-col-5'  => __( '5 columns', 'twodayssss' ),
							'um-user-photos-col-6'  => __( '6 columns', 'twodayssss' ),
						),
						'label'         => __( 'Album columns', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_photos_images_column',
						'type'          => 'select',
						'options'       => array(
							''                      => __( 'No. of columns', 'twodayssss' ),
							'um-user-photos-col-2'  => __( '2 columns', 'twodayssss' ),
							'um-user-photos-col-3'  => __( '3 columns', 'twodayssss' ),
							'um-user-photos-col-4'  => __( '4 columns', 'twodayssss' ),
							'um-user-photos-col-5'  => __( '5 columns', 'twodayssss' ),
							'um-user-photos-col-6'  => __( '6 columns', 'twodayssss' ),
						),
						'label'         => __( 'Photo columns', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_photos_cover_size',
						'type'          => 'text',
						'placeholder'   => __( 'Default : 350 x 350', 'twodayssss' ),
						'label'         => __( 'Album Cover size', 'twodayssss' ),
						'tooltip'       => __( 'You will need to regenerate thumbnails once this value is changed', 'twodayssss' ),
						'size'          => 'small',
					),
					array(
						'id'            => 'um_user_photos_image_size',
						'type'          => 'text',
						'placeholder'   => __( 'Default : 250 x 250', 'twodayssss' ),
						'label'         => __( 'Photo thumbnail size', 'twodayssss' ),
						'tooltip'       => __( 'You will need to regenerate thumbnails once this value is changed', 'twodayssss' ),
						'size'          => 'small',
					)
				)
			);

			return $settings;
		}
	}
}