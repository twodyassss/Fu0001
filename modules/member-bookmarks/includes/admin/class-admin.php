<?php
namespace um_ext\um_user_bookmarks\admin;


if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'um_ext\um_user_bookmarks\admin\Admin' ) ) {


	/**
	 * Class Admin
	 * @package um_ext\um_user_bookmarks\admin
	 */
	class Admin {


		/**
		 * Admin constructor.
		 */
		function __construct() {
			add_filter( 'um_settings_structure', array( &$this, 'extend_settings' ), 10, 1 );
			add_filter( 'um_admin_role_metaboxes', array( &$this, 'um_user_bookmarks_add_role_metabox' ), 10, 1 );
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
				'title'     => __( 'User Bookmarks', 'twodayssss' ),
				'fields'    => array(
					array(
						'id'            => 'um_user_bookmarks_position',
						'type'          => 'select',
						'placeholder'   => '',
						'options'       => array(
							'bottom'    => __( 'Bottom', 'twodayssss' ),
							'top'       => __( 'Top', 'twodayssss' )
						),
						'label'         => __( 'Bookmark icon position', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_bookmarks_post_types',
						'type'          => 'select',
						'multi'         =>  true,
						'placeholder'   => '',
						'options'       => $this->um_user_bookmarks_get_public_post_types(),
						'label'         => __( 'Enable bookmark', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'		 => 'um_user_bookmarks_archive_page',
						'type'	 => 'checkbox',
						'label'	 => __( 'Enable bookmark on archive pages', 'twodayssss' ),
					),
					array(
						'id'            => 'um_user_bookmarks_add_text',
						'type'          => 'text',
						'placeholder'   => '',
						'label'         => __( 'Add bookmark button text', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_bookmarks_remove_text',
						'type'          => 'text',
						'placeholder'   => '',
						'label'         => __( 'Remove bookmark button text', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_bookmarks_folders_text',
						'type'          => 'text',
						'placeholder'   => '',
						'label'         => __( 'Folders text (Plural)', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_bookmarks_folder_text',
						'type'          => 'text',
						'placeholder'   => '',
						'label'         => __( 'Folder text (Singular)', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_bookmarks_bookmarked_icon',
						'type'          => 'text',
						'placeholder'   => '',
						'label'         => __( 'Bookmarked Icon (css class)', 'twodayssss' ),
						'size'          => 'medium',
					),
					array(
						'id'            => 'um_user_bookmarks_regular_icon',
						'type'          => 'text',
						'placeholder'   => '',
						'label'         => __( 'Regular Icon (css class)', 'twodayssss' ),
						'size'          => 'medium',

					)
				)
			);

			return $settings;
		}


		/**
		 * @return array
		 */
		function um_user_bookmarks_get_public_post_types() {
			$public_post_type = array();

			$post_types = get_post_types( array(
				'public'                => true,
				'exclude_from_search'   => false,
				'_builtin'              => false,
				'supports'              => array( 'title', 'editor' )
			), 'objects', 'or' );
			
			if ( count( $post_types ) ) {
				$escape = array(
					'um_private_content',
					'um_directory',
					'um_form',
					'um_user_photos',
					'reply',
					'um_activity',
				);

				foreach ( $post_types as $post_type_key => $post_type_data ) {
					if ( in_array( $post_type_key, $escape ) ) {
						continue;
					}

					$public_post_type[ $post_type_key ] = $post_type_data->label;
				}
			}

			return apply_filters( 'um_user_bookmarks_admin_post_types', $public_post_type );
		}


		/**
		 * @param $roles_metaboxes
		 *
		 * @return array
		 */
		function um_user_bookmarks_add_role_metabox( $roles_metaboxes ) {
			$roles_metaboxes[] = array(
				'id'        => "um-admin-form-bookmark{" . um_user_bookmarks_path . "}",
				'title'     => __( 'User Bookmarks', 'twodayssss' ),
				'callback'  => array( UM()->metabox(), 'load_metabox_role' ),
				'screen'    => 'um_role_meta',
				'context'   => 'normal',
				'priority'  => 'default'
			);

			return $roles_metaboxes;
		}
	}
}