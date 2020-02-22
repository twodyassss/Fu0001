<?php
namespace um_ext\um_mycred\core;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Class myCRED_Account
 * @package um_ext\um_mycred\core
 */
class myCRED_Account {


	/**
	 * myCRED_Account constructor.
	 */
	function __construct() {

		add_filter( 'um_custom_error_message_handler', array( &$this, 'custom_error' ), 10, 2 );
		add_filter( 'um_custom_success_message_handler', array( &$this, 'custom_success' ), 10, 2 );
		add_filter( 'um_account_page_default_tabs_hook', array( &$this, 'account_tab' ), 100 );
		add_filter( 'um_account_content_hook_points', array( &$this, 'points_tab_content' ), 10, 1 );
		add_action( 'mycred_update_user_balance', array( &$this, 'reset_cache' ), 9999, 4 );
		add_action( 'um_social_login_after_provider_title', array( &$this, 'social_login_credit' ), 10, 2 );
		add_action( 'um_submit_account_points_tab_errors_hook', array( &$this, 'transfer_errors' ), 10, 1 );

	}


	/**
	 * Custom Error Message on upgrade account page
	 *
	 * @param string $msg
	 * @param string $err
	 *
	 * @return string
	 */
	function custom_error( $msg, $err ) {

		if ( $err == 'mycred_invalid_amount' ) {
			$msg = __( 'Invalid amount.', 'twodayssss' );
		}

		if ( $err == 'mycred_cant_receive' ) {
			$msg = __( 'That user can not receive points.', 'twodayssss' );
		}

		if ( $err == 'mycred_invalid_user' ) {
			$msg = __( 'The user does not exist.', 'twodayssss' );
		}

		if ( $err == 'mycred_not_enough_balance' ) {
			$msg = __( 'You do not have enough balance.', 'twodayssss' );
		}

		if ( $err == 'mycred_myself' ) {
			$msg = __( 'You can not transfer points to yourself.', 'twodayssss' );
		}

		if ( $err == 'mycred_unauthorized' ) {
			$msg = __( 'You are not allowed to transfer points.', 'twodayssss' );
		}

		return $msg;
	}


	/**
	 * Custom Success Message on upgrade account page
	 *
	 * @param string $msg
	 * @param string $success
	 *
	 * @return string
	 */
	function custom_success( $msg, $success ) {

		if ( $success == 'mycred_transfer_done' ) {
			$msg = __( 'Points transferred successfully', 'twodayssss' );
		}

		return $msg;
	}


	/**
	 * Add tab to account page
	 *
	 * @param $tabs
	 *
	 * @return mixed
	 */
	function account_tab( $tabs ) {

		$tabs[1000]['points']['icon'] = 'um-faicon-trophy';
		$tabs[1000]['points']['title'] = __( 'My Points', 'twodayssss' );
		$tabs[1000]['points']['submit_title'] = __( 'My Points', 'twodayssss' );
		$tabs[1000]['points']['show_button'] = false;

		return $tabs;
	}


	/**
	 * Content to account tab
	 *
	 * @param $output
	 *
	 * @return string
	 */
	function points_tab_content( $output ) {
		wp_enqueue_script( 'um_mycred' );
		wp_enqueue_style( 'um_mycred' );

		ob_start();

		$user_id = get_current_user_id(); ?>

		<div class="um-field um-mycred-account-col" data-key="">
			<div class="um-field-label"><strong><?php echo __('My Balance','twodayssss'); ?></strong></div>
			<div class="um-field-area">
				<span><?php echo UM()->myCRED_API()->get_points( $user_id ); ?></span>
			</div>
		</div>

		<?php if ( um_user('can_transfer_mycred') ) { ?>
			<div class="um-field um-mycred-account-col" data-key="">
				<div class="um-field-label"><strong><?php echo __('Transfer Balance','twodayssss'); ?></strong></div>
				<div class="um-field-area">

					<p><?php printf(__('You can transfer up to %s points to another user.','twodayssss'), UM()->myCRED_API()->get_points_clean( $user_id ) ); ?></p>

					<input type="text" name="mycred_transfer_uid" placeholder="<?php _e('Username, e-mail, or ID','twodayssss'); ?>" class="um-mycred-input" />

					<p><?php _e('Enter amount below','twodayssss'); ?></p>

					<input type="text" name="mycred_transfer_amount" placeholder="0.00" class="um-mycred-amount" />
					<input type="submit" name="um_account_submit" id="um_account_submit_mycred_transfer" value="<?php esc_attr_e( 'Confirm Transfer', 'twodayssss' ); ?>" class="um-mycred-send-points um-button" />

					<p><?php _e('This is not reversible once you click confirm transfer.','twodayssss'); ?></p>

				</div>
			</div>
		<?php } ?>

		<?php $mycred_referrak_link = apply_filters('um_mycred_enable_referrak_link', true ); ?>
		<?php if ( UM()->options()->get('mycred_refer') && $mycred_referrak_link ) { ?>

			<div class="um-field um-mycred-account-col" data-key="">
				<div class="um-field-label"><strong><?php _e('My Referral Link','twodayssss'); ?></strong></div>
				<div class="um-field-area">
					<a href="<?php echo do_shortcode('[mycred_affiliate_link url='. get_bloginfo('url') . ']'); ?>" target="_blank"><?php echo do_shortcode('[mycred_affiliate_link url='. get_bloginfo('url') . ']'); ?></a>
				</div>
			</div>

		<?php }

		$output .= ob_get_clean();
		return $output;
	}


	/**
	 * Reset user cached balance
	 *
	 * @param int $user_id
	 * @param $current_balance
	 * @param $amount
	 * @param $type
	 */
	function reset_cache( $user_id, $current_balance, $amount, $type ) {
		delete_option( "um_cache_userdata_{$user_id}" );
	}


	/**
	 * On account page when social login is enabled
	 *
	 * @param $provider
	 * @param $array
	 */
	function social_login_credit( $provider, $array ) {
		if ( ! UM()->options()->get( 'mycred_' . $provider ) ) {
			return;
		}

		if ( UM()->Social_Login_API()->is_connected( get_current_user_id(), $provider ) ) {
			return;
		}

		wp_enqueue_script( 'um_mycred' );
		wp_enqueue_style( 'um_mycred' );

		$points = UM()->options()->get( 'mycred_' . $provider . '_points' ); ?>

		<div class="um-mycred-light">
			<?php printf( __( 'Add %s points to your balance by connecting to this network.', 'twodayssss' ), $points ); ?>
		</div>

		<?php
	}


	/**
	 * Errors/Success for transferring points
	 *
	 * @param $args
	 */
	function transfer_errors( $args ) {
		if ( ! empty( $_POST['mycred_transfer_uid'] ) && ! empty( $_POST['mycred_transfer_amount'] ) ) {

			$user = $_POST['mycred_transfer_uid'];
			$amount = $_POST['mycred_transfer_amount'];

			if ( ! um_user( 'can_transfer_mycred' ) ) {
				$r = UM()->account()->tab_link( 'points' );
				$r = add_query_arg( 'err', 'mycred_unauthorized', $r );
				exit( wp_redirect( $r ) );
			}

			if ( is_numeric( $user ) ) {
				if ( $user == get_current_user_id() ) {
					$r = UM()->account()->tab_link( 'points' );
					$r = add_query_arg( 'err', 'mycred_myself', $r );
					exit( wp_redirect( $r ) );
				}
				if ( ! UM()->user()->user_exists_by_id( $user ) ) {
					$r = UM()->account()->tab_link( 'points' );
					$r = add_query_arg( 'err', 'mycred_invalid_user', $r );
					exit( wp_redirect( $r ) );
				}
			} else {
				if ( ! username_exists( $user ) && ! email_exists( $user ) ) {
					$r = UM()->account()->tab_link( 'points' );
					$r = add_query_arg( 'err', 'mycred_invalid_user', $r );
					exit( wp_redirect( $r ) );
				}
			}

			if ( is_numeric( $user ) ) {
				$user_id = $user;
			} elseif ( is_email( $user ) ) {
				$user_id = email_exists( $user );
			} else {
				$user_id = username_exists( $user );
			}

			// check if user can receive points
			um_fetch_user( $user_id );
			if ( um_user( 'cannot_receive_mycred' ) ) {
				$r = UM()->account()->tab_link( 'points' );
				$r = add_query_arg( 'err', 'mycred_cant_receive', $r );
				exit( wp_redirect( $r ) );
			}

			if ( ! is_numeric( $amount ) ) {
				$r = UM()->account()->tab_link( 'points' );
				$r = add_query_arg( 'err', 'mycred_invalid_amount', $r );
				exit( wp_redirect( $r ) );
			}

			if ( $amount > UM()->myCRED_API()->get_points_clean( get_current_user_id() ) ) {
				$r = UM()->account()->tab_link( 'points' );
				$r = add_query_arg( 'err', 'mycred_not_enough_balance', $r );
				exit( wp_redirect( $r ) );
			}

			UM()->myCRED_API()->transfer( get_current_user_id(), $user_id, $amount );
			$r = UM()->account()->tab_link( 'points' );
			$r = add_query_arg( 'updated', 'mycred_transfer_done', $r );
			exit( wp_redirect( $r ) );

		}
	}
}