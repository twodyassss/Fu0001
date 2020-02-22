<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="um-admin-metabox">

	<h6><?php _e('Shortcode','twodayssss'); ?></h6>
	<p>[ultimatemember_notice id="<?php echo get_the_ID(); ?>"]</p>
	
	<?php $flush = add_query_arg('um_adm_action','flush_notice', UM()->permalinks()->get_current_url() );
	$flush = add_query_arg('notice_id',get_the_ID(), $flush );
	
	$count = 0;
	$users = get_post_meta( get_the_ID(), '_users', true );
	if ( is_array( $users ) ) {
		$count = count($users);
	} ?>
	
	<div class="p_seperate">
		<h6><?php _e('Reach','twodayssss'); ?></h6>
		<p><span class="um-admin-icontext"><i class="um-icon-stats-bars"></i> <?php echo $count; ?></span></p>
	</div>

	<div class="p_seperate">
		<h6><?php _e('Flush','twodayssss'); ?></h6>
		<p><?php _e('Flushing a notice makes it appear as a new notice to users even those who have seen it already.','twodayssss'); ?></p>
		<p><a href="<?php echo $flush; ?>" class="button"><?php _e('Flush this notice','twodayssss'); ?></a></p>
	</div>

</div>