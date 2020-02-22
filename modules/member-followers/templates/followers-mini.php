<?php if ( ! defined( 'ABSPATH' ) ) exit;  ?>

<div class="um-followers-m" data-max="<?php echo esc_attr( $max ); ?>">

	<?php if ( $followers ) {
		foreach ( $followers as $k => $arr ) {
			/**
			 * @var int $user_id2
			 */
			extract( $arr );
			um_fetch_user( $user_id2 );  ?>

			<div class="um-followers-m-user">
				<div class="um-followers-m-pic">
					<a href="<?php echo esc_attr( um_user_profile_url() ); ?>" class="um-tip-n" title="<?php echo esc_attr( um_user( 'display_name' ) ); ?>">
						<?php echo get_avatar( um_user('ID'), 40 ); ?>
					</a>
				</div>
			</div>
	
		<?php }
	} else { ?>

		<p>
			<?php echo ( $user_id == get_current_user_id() ) ? __( 'You do not have any followers yet.', 'twodayssss' ) : __( 'This user do not have any followers yet.', 'twodayssss' ); ?>
		</p>

	<?php } ?>

</div>
<div class="um-clear"></div>