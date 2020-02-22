<?php
namespace um_ext\um_friends\core;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Friends_Shortcode
 * @package um_ext\um_friends\core
 */
class Friends_Shortcode {


	/**
	 * Friends_Shortcode constructor.
	 */
	function __construct() {

		add_shortcode( 'ultimatemember_friends_online', array( &$this, 'ultimatemember_friends_online' ) );
		add_shortcode( 'ultimatemember_friends', array( &$this, 'ultimatemember_friends' ) );
		add_shortcode( 'ultimatemember_friend_reqs', array( &$this, 'ultimatemember_friend_reqs' ) );
		add_shortcode( 'ultimatemember_friend_reqs_sent', array( &$this, 'ultimatemember_friend_reqs_sent' ) );
		add_shortcode( 'ultimatemember_friends_bar', array( &$this, 'ultimatemember_friends_bar' ) );
	}


	/**
	 * Shortcode "FRIENDS ONLINE"
	 *
	 * @example [ultimatemember_friends_online]
	 *
	 * @param array $args
	 *  'user_id' => {current user ID},
	 *  'style' => 'default',
	 *  'max' => 12
	 *
	 * @return string
	 */
	public function ultimatemember_friends_online( $args = array() ) {

		$defaults = array(
			'user_id'   => ( um_is_core_page( 'user' ) ) ? um_profile_id() : get_current_user_id(),
			'style'     => 'default',
			'max'       => 12
		);
		$args = shortcode_atts( $defaults, $args );

		/**
		 * @var $style
		 * @var $user_id
		 */
		extract( $args );

		if ( $style == 'avatars' ) {
			$tpl = 'friends-mini';
		} else {
			$tpl = 'friends';
		}

		$online_ids = apply_filters( 'um-friends-online-users', array(), $args );
		if ( empty( $online_ids ) ) {
			return '';
		}

		$friends = UM()->Friends_API()->api()->friends( $user_id );
		if ( ! empty( $friends ) ) {
			foreach ( $friends as $k => $v ) {
				if ( empty( array_intersect( $online_ids, array_diff( $v, array( $user_id ) ) ) ) ) {
					unset( $friends[ $k ] );
				}
			}
		}

		if ( empty( $friends ) ) {
			return '';
		}

		$t_args = array_merge( $args, array( 'friends' => $friends ) );
		$output = twodays_get_template( "$tpl.php", um_friends_plugin, $t_args );

		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

		return $output;
	}


	/**
	 * Shortcode
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	function ultimatemember_friends_bar( $args = array() ) {

		$defaults = array(
				'user_id' => um_profile_id()
		);
		$args = wp_parse_args( $args, $defaults );

		extract( $args );

		$can_view = true;
		if ( !is_user_logged_in() || get_current_user_id() != $user_id ) {

			$is_private_case_old = UM()->user()->is_private_case( $user_id, __( 'Friends only', 'twodayssss' ) );
			$is_private_case = UM()->user()->is_private_case( $user_id, 'friends' );
			if ( $is_private_case || $is_private_case_old ) { // only friends can view my profile
				$can_view = false;
			}
		}

		$t_args = compact( 'args', 'can_view', 'user_id' );
		$output = twodays_get_template( 'friends-bar.php', um_friends_plugin, $t_args );

		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

		return $output;
	}


	/**
	 * Shortcode
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	function ultimatemember_friends( $args = array() ) {
		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

		$defaults = array(
			'user_id' 		=> ( um_is_core_page('user') ) ? um_profile_id() : get_current_user_id(),
			'style' 		=> 'default',
			'max'			=> 11
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		ob_start();

		if ( $style == 'avatars' ) {
			$tpl = 'friends-mini';
		} else {
			$tpl = 'friends';
		}

		$file       = um_friends_path . 'templates/'.$tpl.'.php';
		$theme_file = get_stylesheet_directory() . '/ultimate-member/templates/'.$tpl.'.php';

		if ( file_exists( $theme_file ) ) {
			$file = $theme_file;
		}

		if( file_exists( $file ) ) {
			$friends = UM()->Friends_API()->api()->friends( $user_id );
			include_once $file;
		}

		$output = ob_get_clean();
		return $output;
	}


	/**
	 * Shortcode
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	function ultimatemember_friend_reqs( $args = array() ) {
		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

		$defaults = array(
			'user_id' 		=> ( um_is_core_page('user') ) ? um_profile_id() : get_current_user_id(),
			'style' 		=> 'default',
			'max'			=> 999
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		ob_start();

		if ( $style == 'avatars' ) {
			$tpl = 'friends-mini';
		} else {
			$tpl = 'friends';
		}

		$file       = um_friends_path . 'templates/'.$tpl.'.php';
		$theme_file = get_stylesheet_directory() . '/ultimate-member/templates/'.$tpl.'.php';

		if( file_exists( $theme_file ) ) {
			$file = $theme_file;
		}

		if( file_exists( $file ) ) {
			$friends = UM()->Friends_API()->api()->friend_reqs( $user_id );
			$_is_reqs = true;
			include_once $file;
		}

		$output = ob_get_clean();
		return $output;
	}


	/**
	 * Shortcode
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	function ultimatemember_friend_reqs_sent( $args = array() ) {
		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

		$defaults = array(
			'user_id' 		=> ( um_is_core_page('user') ) ? um_profile_id() : get_current_user_id(),
			'style' 		=> 'default',
			'max'			=> 999
		);
		$args = wp_parse_args( $args, $defaults );
		extract( $args );

		ob_start();

		if ( $style == 'avatars' ) {
			$tpl = 'friends-mini';
		} else {
			$tpl = 'friends';
		}

		$file       = um_friends_path . 'templates/'.$tpl.'.php';
		$theme_file = get_stylesheet_directory() . '/ultimate-member/templates/'.$tpl.'.php';

		if( file_exists( $theme_file ) ) {
			$file = $theme_file;
		}

		if( file_exists( $file ) ) {
			$friends = UM()->Friends_API()->api()->friend_reqs_sent( $user_id );
			$_sent = true;
			include_once $file;
		}

		$output = ob_get_clean();
		return $output;
	}
}
