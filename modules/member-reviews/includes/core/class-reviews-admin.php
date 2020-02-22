<?php
namespace um_ext\um_reviews\core;

if ( ! defined( 'ABSPATH' ) ) exit;

class Reviews_Admin {


	/**
	 * Reviews_Admin constructor.
	 */
	function __construct() {
		$this->pagehook = 'toplevel_page_ultimatemember';

		add_action( 'um_extend_admin_menu', array( &$this, 'admin_menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ), 10 );

		add_action( 'load-post.php', array( &$this, 'add_metabox' ), 9 );
		add_action( 'load-post-new.php', array( &$this, 'add_metabox' ), 9 );

		add_filter( 'parse_query', array( &$this, 'parse_query' ) );
		add_filter( 'views_edit-um_review', array( &$this, 'views_um_review' ) );

		add_filter( 'manage_edit-um_review_columns', array( &$this, 'manage_edit_um_review_columns' ) );
		add_action( 'manage_um_review_posts_custom_column', array( &$this, 'manage_um_review_posts_custom_column' ), 10, 3 );

		add_filter( "um_predefined_fields_hook", array( &$this, 'um_reviews_add_field' ), 20, 1 );

		//admin form fields
		add_filter( "um_render_field_type_rating", array( &$this, 'um_review_field' ), 10, 3 );
		add_filter( "um_render_field_type_from_review", array( &$this, 'um_from_review_field' ), 10, 3 );
		add_filter( "um_render_field_type_to_review", array( &$this, 'um_to_review_field' ), 10, 3 );

		//rest-api
		add_action( 'rest_api_init', array( $this, 'rest_api_user_profile_photo' ) );
	}

	/**
	 * Extends the admin menu
	 */
	function admin_menu() {
		add_submenu_page(
			'ultimatemember',
			__( 'User Reviews', 'twodayssss' ),
			__( 'User Reviews', 'twodayssss' ),
			'manage_options',
			'edit.php?post_type=um_review'
		);
	}


	/**
	 * Enqueue admin scripts/styles
	 */
	function admin_enqueue_scripts() {
		$screen = get_current_screen();

		if ( ! isset( $screen->id ) || ! strstr( $screen->id, 'um_review' ) ) {
			return;
		}

		wp_register_script( 'um_admin_reviews', um_reviews_url . 'includes/admin/assets/js/um-admin-reviews.js', array( 'jquery', 'um_raty' ), um_reviews_version, true );
		wp_register_style( 'um_admin_reviews', um_reviews_url . 'includes/admin/assets/css/um-admin-reviews.css', array( 'um_raty' ), um_reviews_version );

		wp_enqueue_script( 'um_admin_reviews' );
		wp_enqueue_style( 'um_admin_reviews' );

		wp_localize_script( 'um_admin_reviews', 'wpUmReviewsApiSettings', array(
				'root'	 => esc_url_raw( rest_url() ),
				'nonce'	 => wp_create_nonce( 'wp_rest' )
		) );
	}


	/**
	 * Init the metaboxes
	 */
	function add_metabox() {
		global $current_screen;

		if ( $current_screen->id == 'um_review') {
			add_action( 'add_meta_boxes', array( &$this, 'add_metabox_form' ), 1 );
			add_action( 'save_post', array( &$this, 'save_metabox_form' ), 10, 2 );
		}
	}


	/**
	 * Add form metabox
	 */
	function add_metabox_form() {
		add_meta_box(
			'um-admin-reviews-review',
			__( 'This Review', 'twodayssss' ),
			array( &$this, 'load_metabox_form' ),
			'um_review',
			'side',
			'default'
		);
	}


	/**
	 * Load a form metabox
	 *
	 * @param $object
	 * @param $box
	 */
	function load_metabox_form( $object, $box ) {
		$box['id'] = str_replace( 'um-admin-reviews-','', $box['id'] );
		include_once um_reviews_path . 'includes/admin/templates/'. $box['id'] . '.php';
		wp_nonce_field( basename( __FILE__ ), 'um_admin_metabox_reviews_form_nonce' );
	}


	/**
	 * Save form metabox
	 *
	 * @param $post_id
	 * @param $post
	 */
	function save_metabox_form( $post_id, $post ) {
		// validate nonce
		if ( ! isset( $_POST['um_admin_metabox_reviews_form_nonce'] ) || ! wp_verify_nonce( $_POST['um_admin_metabox_reviews_form_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		// validate post type
		if ( $post->post_type != 'um_review' ) {
			return;
		}

		// validate user
		$post_type = get_post_type_object( $post->post_type );
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}

		// validate rating
		if ( empty( $_POST['review']['_rating'] ) ) {
			return;
		}

		// update From and To
		if( !empty( $_POST[ 'review' ][ '_reviewer_id' ] ) ) {
			update_post_meta( $post_id, '_reviewer_id', intval( $_POST[ 'review' ][ '_reviewer_id' ] ) );
		}
		if( !empty( $_POST[ 'review' ][ '_user_id' ] ) ) {
			update_post_meta( $post_id, '_user_id', intval( $_POST[ 'review' ][ '_user_id' ] ) );
		}

		// update reviews
		$status = get_post_meta( $post_id, '_status', true );
		if ( $status == 0 && $_POST['review']['_status'] == 1 ) {
			UM()->Reviews_API()->api()->publish_review( $post_id );
		} elseif ( $status == 1 && $_POST['review']['_status'] == 0 ) {
			UM()->Reviews_API()->api()->undo_review( $post_id );
		}
		update_post_meta( $post_id, '_status', $_POST['review']['_status'] );

		// update rating
		UM()->Reviews_API()->api()->adjust_rating( $post_id, null, intval( $_POST[ 'review' ][ '_rating' ] ) );

		$current_flagged = ! empty( $_POST['review']['_flagged'] ) ? $_POST['review']['_flagged'] : 0;
		update_post_meta( $post_id, '_flagged', $current_flagged );
	}

	/**
	 * Get user avatar for "From" and "To" fields in the "This Review" metabox
	 */
	function rest_api_user_profile_photo() {

		register_rest_field( 'user', 'um_photo', array(
				'get_callback' => function( $user, $field_name, $request ) {
					$size = isset( $request->params['GET']['size'] ) ? $request->params['GET']['size'] : 40;
					return um_get_user_avatar_data( $user['id'], $size );
				},
				'update_callback'	 => null,
				'schema'					 => null
		) );
	}


	/**
	 * @param $q \WP_Query
	 * @return mixed
	 */
	function parse_query( $q ) {
		global $pagenow;

		if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'um_review' ) {

			if ( ! empty( $_REQUEST['status'] ) ) {

				if ( $_REQUEST['status'] == 'flagged' ) {
					$q->set( 'meta_key', '_flagged' );
					$q->set( 'meta_value', 1 );
					$q->set( 'meta_compare', '=' );
				}

				if ( $_REQUEST['status'] == 'approved' ) {
					$q->set( 'meta_key', '_status' );
					$q->set( 'meta_value', 1 );
					$q->set( 'meta_compare', '=' );
				}

				if ( $_REQUEST['status'] == 'pending' ) {
					$q->set( 'meta_key', '_status' );
					$q->set( 'meta_value', 0 );
					$q->set( 'meta_compare', '=' );
				}

			}

		}

		return $q;
	}


	/**
	 * Filters
	 * @param $views
	 *
	 * @return array
	 */
	function views_um_review( $views ) {
		if ( isset( $views['trash'] ) )
			$trash['trash'] = $views['trash'];

		$views = array();

		$array['all'] = __('All','twodayssss');
		$array['approved'] = __('Approved','twodayssss');
		$array['flagged'] = __('Flagged','twodayssss');
		$array['pending'] = __('Pending','twodayssss');

		foreach ( $array as $view => $name ) {
			if ( isset( $_REQUEST['status'] ) && $_REQUEST['status'] == $view ) {
				$class = 'current';
			} else {
				$class = '';
			}
			$views[ $view ] = '<a href="?post_type=um_review&status=' . $view . '" class="' . $class . '">' . $name . '</a>';
		}

		if ( isset( $trash['trash'] ) )
			$views['trash'] = $trash['trash'];

		return $views;
	}


	/**
	 * Custom columns
	 *
	 * @param $columns
	 *
	 * @return mixed
	 */
	function manage_edit_um_review_columns( $columns ) {

		$title = $columns['title'];
		unset( $columns['title'] );
		unset( $columns['date'] );
		unset( $columns['cb'] );

		$columns[ 'title' ] = $title;
		$columns[ 'review_from' ] = __( 'From', 'twodayssss' );
		$columns[ 'review_to' ] = __( 'To', 'twodayssss' );
		$columns[ 'review_rating' ] = __( 'Rating', 'twodayssss' );
		$columns[ 'review_flag' ] = __( 'Flagged', 'twodayssss' );
		$columns[ 'review_approved' ] = __( 'Approved', 'twodayssss' );
		$columns[ 'status' ] = __( 'Status', 'twodayssss' );
		$columns[ 'review_date' ] = __( 'Date', 'twodayssss' );

		return $columns;
	}


	/**
	 * Display cusom columns
	 *
	 * @param string $column_name
	 * @param int $id
	 */
	function manage_um_review_posts_custom_column( $column_name, $id ) {

		switch ( $column_name ) {

			case 'review_from':

				$user_id = get_post_meta( $id, '_reviewer_id', true );
				um_fetch_user( $user_id );
				echo '<a href="'. esc_url( um_user_profile_url() ) .'" target="_blank">'. um_user( 'profile_photo', 32 ) .'</a>';
				break;

			case 'review_to':

				$user_id = get_post_meta( $id, '_user_id', true );
				um_fetch_user( $user_id );
				echo '<a href="'. esc_url( um_user_profile_url() ) .'" target="_blank">'. um_user( 'profile_photo', 32 ) .'</a>';
				break;

			case 'review_rating':

				$rating = get_post_meta( $id, '_rating', true );
				echo '<span class="um-reviews-avg" data-number="5" data-score="'. esc_attr( $rating ) . '"></span>';

				break;

			case 'review_flag':
				$flagged = get_post_meta( $id, '_flagged', true );
				if ( $flagged ) {
					echo '<span class="um-adm-ico inactive um-admin-tipsy-n" title="' . esc_attr__( 'Flagged', 'twodayssss' ).'"><i class="um-faicon-flag"></i></span>';
				}
				break;
				
			case 'review_approved':
				$approved_status = get_post_meta( $id, '_status', true );
				$approved_options = array(
					'0' => __( 'Pending', 'twodayssss' ),
					'1' => __( 'Approved', 'twodayssss' )
				);
				echo isset( $approved_options[ $approved_status ] ) ? $approved_options[ $approved_status ] : __( 'Unknown', 'twodayssss' );
				break;

			case 'status':
				$status = get_post_status( $id );
				echo $status;
				break;

			case 'review_date':
				echo get_the_time( UM()->options()->get( 'review_date_format' ) );
				break;

		}
	}


	/**
	 * @param $fields
	 *
	 * @return mixed
	 */
	function um_reviews_add_field( $fields ) {
		$fields['user_rating'] = array(
			'title'             => __( 'User Rating', 'twodayssss' ),
			'metakey'           => 'user_rating',
			'type'              => 'text',
			'label'             => __( 'User Rating', 'twodayssss' ),
			'required'          => 0,
			'public'            => 1,
			'editable'          => 0,
			'icon'              => 'um-faicon-star',
			'edit_forbidden'    => 1,
			'show_anyway'       => true,
			'custom'            => true,
		);

		return $fields;
	}


	/**
	 * Show rating field at admin forms
	 *
	 * @param $html
	 * @param $field_data
	 * @param $form_data
	 *
	 * @return string
	 */
	function um_review_field( $html, $field_data, $form_data ) {
		$name = $field_data['id'];
		$name = ! empty( $form_data['prefix_id'] ) ? $form_data['prefix_id'] . '[' . $name . ']' : $name;

		$default = isset( $field_data['default'] ) ? $field_data['default'] : '';
		$value = isset( $field_data['value'] ) ? $field_data['value'] : $default;

		$html .= '<span class="um-reviews-rate" data-key="' . $name . '" data-number="5" data-score="'. $value . '"></span>';

		return $html;
	}


	/**
	 * Show review from field at admin forms
	 *
	 * @param $html
	 * @param $field_data
	 * @param $form_data
	 *
	 * @return string
	 */
	function um_from_review_field( $html, $field_data, $form_data ) {
		$default = isset( $field_data['default'] ) ? $field_data['default'] : '';
		$value = isset( $field_data['value'] ) ? $field_data['value'] : $default;

		um_fetch_user( $value );
		$html .= '<a href="' . um_user_profile_url() . '" target="_blank">' . um_user( 'profile_photo', 40 ) . '</a>';

		$html .= wp_dropdown_users( array(
				'echo'		 => false,
				'id'			 => $field_data[ 'id' ],
				'name'		 => $form_data['prefix_id'] . '[' . $field_data[ 'id' ] . ']',
				'orderby'	 => 'display_name',
				'order'		 => 'ASC',
				'selected' => $field_data[ 'value' ] ? $field_data[ 'value' ] : get_current_user_id(),
				'show'		 => 'display_name',
		) );

		return $html;
	}


	/**
	 * Show review to field at admin form
	 *
	 * @param $html
	 * @param $field_data
	 * @param $form_data
	 *
	 * @return string
	 */
	function um_to_review_field( $html, $field_data, $form_data ) {
		$default = isset( $field_data['default'] ) ? $field_data['default'] : '';
		$value = isset( $field_data['value'] ) ? $field_data['value'] : $default;

		um_fetch_user( $value );
		$html .= '<a href="' . um_user_profile_url() . '" target="_blank">' . um_user( 'profile_photo', 40 ) . '</a>';

		$html .= wp_dropdown_users( array(
				'echo'		 => false,
				'id'			 => $field_data[ 'id' ],
				'name'		 => $form_data['prefix_id'] . '[' . $field_data[ 'id' ] . ']',
				'orderby'	 => 'display_name',
				'order'		 => 'ASC',
				'selected' => $field_data[ 'value' ] ? $field_data[ 'value' ] : get_current_user_id(),
				'show'		 => 'display_name',
		) );

		return $html;
	}

	//class end
}
