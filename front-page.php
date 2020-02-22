<?php

if ( is_user_logged_in() ) {
    
	include( get_home_template() );

} else {

get_header(); 

?>
<?php get_template_part( 'template-parts/front-page/cover' );?>

<?php get_template_part( 'template-parts/front-page/content' ); ?>
<!-- /.jumbotron text-center -->
<div class="">

    <div id="primary" class="content-area">

        <main id="main" class="site-main">

            <?php if ( have_posts() ) : 
					
						while ( have_posts() ) : the_post();

						//	

						endwhile; // End of the loop.
				 
					endif; ?>

        </main><!-- #main -->

    </div><!-- #primary -->

</div>

<?php get_footer();?>

<?php } ?>