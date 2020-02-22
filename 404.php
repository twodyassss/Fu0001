<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Twodays
 */

get_header(); ?>

	<div class="container">
		<div class="row">

			<div class="col-md-8 wp-bp-content-width">

				<div id="primary" class="content-area wp-bp-404">
					<main id="main" class="site-main">

						<div class="card mt-3r">
							<div class="card-body">
								<section class="error-404 not-found">
									<header class="page-header">
										<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'twodayssss' ); ?></h1>
									</header><!-- .page-header -->

									<div class="page-content">
										<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'twodayssss' ); ?></p>

										<?php
											get_search_form();

											the_widget( 'WP_Widget_Recent_Posts', array(), array(
												'before_title' => '<h5 class="widget-title mt-4">',
												'after_title'  => '</h5>',
											) );
										?>

										<div class="widget widget_categories">
											<h5 class="widget-title"><?php esc_html_e( 'Most Used Categories', 'twodayssss' ); ?></h5>
											<ul>
											<?php
												wp_list_categories( array(
													'orderby'    => 'count',
													'order'      => 'DESC',
													'show_count' => 1,
													'title_li'   => '',
													'number'     => 10,
												) );
											?>
											</ul>
										</div><!-- .widget -->

										<?php

											the_widget( 'WP_Widget_Archives', 'dropdown=1', array(
												'before_title' => '<h5 class="widget-title">',
												'after_title'  => '</h5>',
											) );

											the_widget( 'WP_Widget_Tag_Cloud', array(), array(
												'before_title' => '<h5 class="widget-title">',
												'after_title'  => '</h5>',
											) );
										?>

									</div><!-- .page-content -->
								</section><!-- .error-404 -->
							</div>
							<!-- /.card-body -->
						</div>
						<!-- /.card -->

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
