<?php if ( ( is_single() || ( is_page() && ! twodays_is_frontpage() ) ) && has_post_thumbnail( get_queried_object_id() ) ) : ?>
	<section class="td-bp-full-page-header" style="background-image: url(<?php echo get_the_post_thumbnail_url( get_queried_object_id(), 'full' );?>)">
		<div class="page-header-overlay" style="background-color: rgba(0, 0, 0, 0.5);">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-8 text-center">
						<h1><span itemprop="name"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;"><?php echo get_the_title();?></font></font></span></h1>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>
