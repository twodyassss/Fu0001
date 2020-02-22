<?php
/**
 * The template for displaying archive pages
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
					if ( have_posts() ) : ?>

						<header class="page-header mt-3r">
							<?php
								the_archive_title( '<h1 class="page-title">', '</h1>' );
								the_archive_description( '<div class="archive-description text-muted">', '</div>' );
							?>
						</header><!-- .page-header -->
						<div id="post-list" class="row">
						<?php
						/* Start the Loop */
						while ( have_posts() ) : the_post();

							/*
							 * Include the Post-Format-specific template for the content.
							 * If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'archive' );

						endwhile;
						?>
						</div>
						<?php
						the_posts_navigation( array(
							'next_text' => esc_html__( 'Newer Posts', 'twodayssss' ),
							'prev_text' => esc_html__( 'Older Posts', 'twodayssss' ),
						) );

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; ?>

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
