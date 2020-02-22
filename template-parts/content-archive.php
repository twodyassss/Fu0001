<?php
/**
 * Template part for displaying results in search pages
 *
 * @package Twodays
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-sm-6 col-md-4 col-lg-4 mt-3r' ); ?>>
	
	<div class="card card--hover">
		<?php if ( is_sticky() ) : ?>
			<span class="oi oi-bookmark wp-bp-sticky text-muted" title="<?php echo esc_attr__( 'Sticky Post', 'twodayssss' ); ?>"></span>
		<?php endif; ?>
		<?php twodays_post_thumbnail(); ?>

		<div class="card-body">
			<button type="button" class="btn btn-primary btn-lg btn-block">
				<?php the_title(); ?><span class="badge badge-light ml-1r">4</span>
			</button>
			<?php //the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<?php if ( 'post' === get_post_type() ) : ?>
			<footer class="entry-footer card-footer text-muted">
				<?php twodays_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
