<?php
$default_cover_title = get_bloginfo( 'name' );
?>
<section class="jumbotron bg-white text-center wp-bs-4-jumbotron border-bottom">
			<div class="wp-bp-jumbo-overlay">
				<div class="container">
					<h1 class="jumbotron-heading text-white mb-4"><?php echo wp_kses_post( $default_cover_title ); ?></h1>
					<?php 
						if ( get_theme_mod( 'show_site_description', 1 ) ) {
		                    $default_cover_lead = get_bloginfo( 'description', 'display' );
		                    if ( $default_cover_lead || is_customize_preview() ) : ?>
		                        <p class="lead text-white"><?php echo wp_kses_post( $default_cover_lead ); ?></p>
								<p class="lead text-white mb-4">Take advantage of the early bird pricing now!</p> 
		                    <?php
		                    endif;
		                }
					?>	
					<a href="<?php echo home_url('/register/')?>" class="btn btn-primary" style="margin:2rem">JOIN NOW</a>
				</div>
			</div>
			<!-- /.container -->
</section>
