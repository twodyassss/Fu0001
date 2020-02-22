<?php
namespace um_ext\um_messaging\core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class Messaging_Profile
 * @package um_ext\um_messaging\core
 */
class Messaging_Profile {


	/**
	 * Messaging_Profile constructor.
	 */
	function __construct() {
		add_action( 'um_profile_navbar', array( &$this, 'add_profile_bar' ), 5 );
		add_filter( 'um_profile_navbar_classes', array( &$this, 'profile_navbar_classes' ), 10, 1 );

		add_filter( 'um_profile_tabs', array( &$this, 'add_tab' ), 200 );

		add_action( 'um_profile_content_messages_default', array( &$this, 'content_messages_default' ) );

		add_filter( 'um_profile_tag_hook__new_messages', array( &$this, 'unread_count_messages' ), 10, 2 );

		add_action( 'um_profile_footer', array( &$this, 'profile_footer_login_form' ), 99, 1 );
	}


	/**
	 * Customize the nav bar
	 *
	 * @param $args
	 */
	function add_profile_bar( $args ) {
		$user_id = um_profile_id();

		if ( is_user_logged_in() ) {
			if ( get_current_user_id() == $user_id ) {
				return;
			}

			if ( ! UM()->Messaging_API()->api()->can_message( $user_id ) ) {
				return;
			}
		}

		wp_enqueue_script( 'twodayssss' );
		wp_enqueue_style( 'twodayssss' ); ?>

		<div class="um-messaging-btn">
			<?php echo do_shortcode( '[ultimatemember_message_button user_id="' . $user_id . '"]' ) ?>
		</div>
		<?php
	}


	/**
	 * @param string $classes
	 * @return string
	 */
	function profile_navbar_classes( $classes ) {
		$classes .= ' um-messaging-bar';
		return $classes;
	}


	/**
	 * Messaging profile tab
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	function add_tab( $tabs ) {
		if ( ! is_user_logged_in() ) {
			return $tabs;
		}

		if ( ! is_admin() && ! um_user('enable_messaging' ) ) {
			return $tabs;
		}

		$tabs['messages'] = array(
			'name'              => __( 'Messages', 'twodayssss' ),
			'icon'              => 'um-faicon-envelope-o',
			'default_privacy'   => 3,
		);

		$from_config = apply_filters( 'um_get_tabs_from_config', false );

		if ( $from_config ) {
			return $tabs;
		}

		if ( ! is_admin() && um_user( 'can_read_pm' ) && um_is_myprofile() ) {
			$count = UM()->Messaging_API()->api()->get_unread_count( um_profile_id() );
			$tabs['messages']['notifier'] = ( $count > 10 ) ? 10 . '+' : $count;
		}

		return $tabs;
	}


	/**
	 * Default tab
	 *
	 * @param $args
	 */
	function content_messages_default( $args ) {
		echo do_shortcode( '[ultimatemember_messages user_id="' . um_profile_id() . '" /]' );
	}


	/**
	 * Display unread messages count in menu
	 *
	 * @param $value
	 * @param $user_id
	 *
	 * @return string
	 */
	function unread_count_messages( $value, $user_id ) {
		wp_enqueue_script( 'twodayssss' );
		wp_enqueue_style( 'twodayssss' );

		$count = UM()->Messaging_API()->api()->get_unread_count( $user_id );
		return '<span class="um-message-unreaditems count-'. $count . '">' . ( ( $count > 10 ) ? 10 . '+' : $count ) . '</span>';
	}



	/**
	 * Insert Login form to hidden block
	 *
	 * @param array $args
	 */
	function profile_footer_login_form( $args ) {
		if ( is_user_logged_in() ) {
			return;
		}

		wp_enqueue_script( 'twodayssss' );
		wp_enqueue_style( 'twodayssss' );

		if ( ! empty( $_COOKIE['um_messaging_invite_login'] ) ) {
			$_POST = array_merge( json_decode( wp_unslash( $_COOKIE['um_messaging_invite_login'] ), true ), $_POST );
			UM()->form()->form_init();
		}

		add_filter( 'um_browser_url_redirect_to__filter', array( UM()->Messaging_API()->api(), 'set_redirect_to' ), 10, 1 ); ?>

		<div class="um_messaging_hidden_login" style="display: none;">
			<?php echo do_shortcode( '[ultimatemember form_id="' . UM()->shortcodes()->core_login_form() . '"]' ); ?>
		</div>

		<?php
	}
}