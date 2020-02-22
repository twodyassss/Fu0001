<?php
/**
 * Template part for displaying results in search pages
 *
 * @package Twodays
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'col-sm-6 col-md-4 col-lg-4 mt-3r' ); ?>>
	<div class="card">
		<?php twodays_post_thumbnail(); ?>
		
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title card-title"><a href="%s" rel="bookmark" class="text-dark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	
	<!-- /.card-body -->

	<?php if ( 'post' === get_post_type() ) : ?>
		<footer class="entry-footer card-footer text-muted">
			<?php twodays_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
	</div>
</article><!-- #post-<?php the_ID(); ?> -->
