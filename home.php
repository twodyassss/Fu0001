<?php
/**
 * The main template file
 *
 * @package Twodays
 */

get_header(); ?>

	<div class="container-fluid content">
			
		<div class="row flex-xl-nowrap">
				
			<div class="col-md-4 fixed-sidebar">	
				<?php get_sidebar('tag'); ?>
			</div>
			
				<div class="col-md-8 -content">
			
					<div id="primary" class="content-area">
						
						<main id="main" class="site-main">

							<?php
							
							if ( have_posts() ) : ?>
							
								<div class="entry-content">
									
									<div class="um-member-directory-search-line float-right">
										<?php twodays_get_member_searchform(); ?>
									</div>
								
								</div>
								<?php get_template_part( 'template-parts/posts-slider' );?>
									
									<div id="post-list" class="mt-1-5r row">
									
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
									'next_text' => esc_html__( 'Newer member', 'twodayssss' ),
									'prev_text' => esc_html__( 'Older member', 'twodayssss' ),
								) );

							else :

								get_template_part( 'template-parts/content', 'none' );

							endif; ?>

						</main><!-- #main -->
					
					</div><!-- #primary -->
				
				</div>
			
			<!-- /.col-md-4 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->
<?php
get_footer();