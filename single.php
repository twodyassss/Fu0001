<?php
/**
 * The template for displaying all single posts
 *
 * @package Twodays
 */

get_header(); ?>

	<div class="container content">
		
		<div class="row">
			
			<div class="col-md-8 wp-bp-content-width">
				
				<div id="primary" class="content-area">
					
					<main id="main" class="site-main">

					<?php
						while ( have_posts() ) : the_post();

							get_template_part( 'template-parts/content', get_post_type() );

							the_post_navigation(array(
								'prev_text' => esc_html__( '&laquo; Previous Post', 'twodayssss' ),
								'next_text' => esc_html__( 'Next Post &raquo;', 'twodayssss' ),
							) );

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) :
								comments_template();
							endif;

						endwhile; // End of the loop.
					?>

					</main><!-- #main -->
				
				</div><!-- #primary -->
			
			</div>
			<!-- /.col-md-8 -->

			<div class="col-md-4 wp-bp-sidebar-width">
				
				<?php get_sidebar(); ?>
			
			</div>
			<!-- /.col-md-4 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->
<?php
get_footer();
