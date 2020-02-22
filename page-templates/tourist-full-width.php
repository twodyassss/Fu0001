<?php
/*
* Template Name: Tourist Full Width(游客模板)
*/

get_header(); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main">
					<?php
						while ( have_posts() ) : the_post();

						get_template_part( 'template-parts/content', 'page' );

						endwhile; // End of the loop.
					?>
					<?php twodays_frontpage_template_login();?>
					</main><!-- #main -->
                </div><!-- #primary -->
            </div>
            <!-- /.col-md-8 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container -->
<?php
get_footer();
