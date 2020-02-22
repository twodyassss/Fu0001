<?php
/**
 * The main template file
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
						if ( have_posts() ) :

							if ( is_home() && ! is_front_page() ) : ?>
								<header>
									<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
								</header>

							<?php
							endif;

							if( is_home() && !is_paged() ) {
								get_template_part( 'template-parts/posts-slider' );
							}
							?>
							
							<div class="row">
							
							<?php
							/* Start the Loop */
							while ( have_posts() ) : the_post();
							
								// Include the Post-Format-specific template for the content.
								get_template_part( 'template-parts/content', get_post_format() );
								
							endwhile;
							
							?>
							
							</div>
							
							<?php
								the_posts_navigation( array(
									'next_text' => esc_html__( 'Newer Posts', 'twodayssss' ),
									'prev_text' => esc_html__( 'Older Posts', 'twodayssss' ),
								) );
							?>
						
						<?php
						else :

							get_template_part( 'template-parts/content', 'none' );

						endif; ?>

					</main><!-- #main -->
				
				</div><!-- #primary -->
			
			</div>
			<!-- /.col-md-8 -->

		
			<div class="col-md-4 order-md-first wp-bp-sidebar-width">
				
				<?php get_sidebar(); ?>
			
			</div>
			<!-- /.col-md-4 -->
		
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->

<?php
get_footer();
