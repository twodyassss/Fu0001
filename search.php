<?php
/**
 * The template for displaying search results pages
 *
 * @package Twodays
 */

get_header(); ?>

	<div class="container">
		<div class="row">

			<div class="col-md-8 wp-bp-content-width">
				<section id="primary" class="content-area">
					<main id="main" class="site-main">

					<?php
					if ( have_posts() ) : ?>

						<header class="page-header mt-3r">
							<h1 class="page-title"><?php
								/* translators: %s: search query. */
								printf( esc_html__( 'Search Results for: %s', 'twodayssss' ), '<span>' . get_search_query() . '</span>' );
							?></h1>
						</header><!-- .page-header -->

						<?php
						/* Start the Loop */
						while ( have_posts() ) : the_post();

							/**
							 * Run the loop for the search to output the results.
							 * If you want to overload this in a child theme then include a file
							 * called content-search.php and that will be used instead.
							 */
							get_template_part( 'template-parts/content', 'search' );

						endwhile;

						the_posts_navigation( array(
							'next_text'         => esc_html__( 'Newer Posts', 'twodayssss' ),
							'prev_text'         => esc_html__( 'Older Posts', 'twodayssss' ),
						) );

					else :

						get_template_part( 'template-parts/content', 'none' );

					endif; ?>

					</main><!-- #main -->
				</section><!-- #primary -->
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
