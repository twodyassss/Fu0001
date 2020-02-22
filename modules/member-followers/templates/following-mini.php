<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>


<div class="um-followers-m" data-max="<?php echo $max; ?>">

	<?php if ( $following ) {

		foreach ( $following as $k => $arr ) {
			extract( $arr );
			um_fetch_user( $user_id1 ); ?>

			<div class="um-followers-m-user">
				<div class="um-followers-m-pic">
					<a href="<?php echo esc_attr( um_user_profile_url() ); ?>" class="um-tip-n" title="<?php echo esc_attr( um_user( 'display_name' ) ); ?>">
						<?php echo get_avatar( um_user( 'ID' ), 40 ); ?>
					</a>
				</div>
			</div>

		<?php }

	} else { ?>

		<p>
			<?php echo ( $user_id == get_current_user_id() ) ? __( 'You did not follow anybody yet.', 'twodayssss' ) : __( 'This user did not follow anybody yet.', 'twodayssss' ); ?>
		</p>

	<?php } ?>

</div>
<div class="um-clear"></div>