<?php
namespace um_ext\um_notices\core;

if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Notices_Metabox
 * @package um_ext\um_notices\core
 */
class Notices_Metabox {


	/**
	 * Notices_Metabox constructor.
	 */
	function __construct() {
		add_action( 'load-post.php', array(&$this, 'add_metabox'), 9 );
		add_action( 'load-post-new.php', array(&$this, 'add_metabox'), 9 );
	}


	/**
	 * Init the metaboxes
	 */
	function add_metabox() {
		global $current_screen;
		
		if( $current_screen->id == 'um_notice'){
			add_action( 'add_meta_boxes', array(&$this, 'add_metabox_form'), 1 );
			add_action( 'save_post', array(&$this, 'save_metabox_form'), 10, 2 );
		}
	}


	/**
	 * Add form metabox
	 */
	function add_metabox_form() {
		add_meta_box('um-admin-notices-options', __('Options','twodayssss'), array(&$this, 'load_metabox_form'), 'um_notice', 'normal', 'default');
		add_meta_box('um-admin-notices-rules', __('Footer Rules','twodayssss'), array(&$this, 'load_metabox_form'), 'um_notice', 'normal', 'default');
		add_meta_box('um-admin-notices-styling', __('Styling','twodayssss'), array(&$this, 'load_metabox_form'), 'um_notice', 'normal', 'default');
		add_meta_box('um-admin-notices-cta', __('CTA (Call to Action)','twodayssss'), array(&$this, 'load_metabox_form'), 'um_notice', 'normal', 'default');
		
		add_meta_box('um-admin-notices-notice', __('This Notice','twodayssss'), array(&$this, 'load_metabox_form'), 'um_notice', 'side', 'default');
	}


	/**
	 * Load a form metabox
	 *
	 * @param $object
	 * @param $box
	 */
	function load_metabox_form( $object, $box ) {
		$box['id'] = str_replace( 'um-admin-notices-','', $box['id'] );
		include_once um_notices_path . 'includes/admin/templates/'. $box['id'] . '.php';
		wp_nonce_field( basename( __FILE__ ), 'um_admin_metabox_notices_form_nonce' );
	}


	/**
	 * Save form metabox
	 *
	 * @param $post_id
	 * @param $post
	 */
	function save_metabox_form( $post_id, $post ) {
		// validate nonce
		if ( ! isset( $_POST['um_admin_metabox_notices_form_nonce'] ) || ! wp_verify_nonce( $_POST['um_admin_metabox_notices_form_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		// validate post type
		if ( $post->post_type != 'um_notice' ) {
			return;
		}
		
		// validate user
		$post_type = get_post_type_object( $post->post_type );
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}

		// save
		delete_post_meta( $post_id, '_um_roles' );
		foreach ( $_POST['notice'] as $k => $v ) {
			if ( strstr( $k, '_um_' ) ) {
				update_post_meta( $post_id, $k, $v );
			}
		}
	}
}