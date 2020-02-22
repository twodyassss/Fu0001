<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
//do_action( 'woocommerce_before_main_content' );

?>
<div class="container">
	
	<div class="row">
				
		<div class="col wp-bp-content-width">
				
			<div id="primary" class="content-area">
				
				<main id="main" class="site-main" role="main">
					
					<div class="mt-3r">

						<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
							
							<h1 class="display-4">套餐价目表</h1>
								
								<p class="lead"></p>
						</div>
						
						<?php
						if ( woocommerce_product_loop() ) {

						/**
						* Hook: woocommerce_before_shop_loop.
						*
						* @hooked woocommerce_output_all_notices - 10
						* @hooked woocommerce_result_count - 20
						* @hooked woocommerce_catalog_ordering - 30
						*/
						//do_action( 'woocommerce_before_shop_loop' );

						woocommerce_product_loop_start();

						if ( wc_get_loop_prop( 'total' ) ) {
							while ( have_posts() ) {
								the_post();

								/**
								* Hook: woocommerce_shop_loop.
								*/
								do_action( 'woocommerce_shop_loop' );

								wc_get_template_part( 'content', 'product' );
								}
							}

						woocommerce_product_loop_end();

								/**
								* Hook: woocommerce_after_shop_loop.
								*
								* @hooked woocommerce_pagination - 10
								*/
								do_action( 'woocommerce_after_shop_loop' );
						} else {
								/**
								* Hook: woocommerce_no_products_found.
								*
								* @hooked wc_no_products_found - 10
								*/
								do_action( 'woocommerce_no_products_found' );
						}

						?>
					</div>
				
				</main><!-- #main -->
			
			</div><!-- #primary -->
		
		</div>
						
	</div><!-- /.row -->

<?php
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
//do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
