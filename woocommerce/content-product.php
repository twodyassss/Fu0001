<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<div class="col-md-4">
<div <?php wc_product_class( 'card mb-4 shadow-sm', $product ); ?>>
	
	<div class="card-header">
		<?php echo '<h4 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'my-0 font-weight-normal' ) ) . '">' . get_the_title() . '</h4>'; ?>
    </div>

	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	global $product;

		$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
	
	echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
	//do_action( 'woocommerce_before_shop_loop_item' );

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	//wc_get_template( 'loop/sale-flash.php' );
	echo woocommerce_get_product_thumbnail();
	//do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	//do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	echo '<div class="card-body">';
	
	//wc_get_template( 'loop/rating.php' );
	//wc_get_template( 'loop/price.php' );
	if ( $price_html = $product->get_price_html() ) :
	echo '<h1 class="card-title pricing-card-title">'. $price_html .'<small class="text-muted">/ 月</small></h1>';
	endif;
	//do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	wc_get_template( 'single-product/short-description.php' );
	echo '<a type="bottom"  href="' . esc_url( $link ) . '" class="btn btn-primary mx-2 my-2">';
	echo '购买套餐</a>';
	echo '</div>';
	//woocommerce_template_loop_add_to_cart();
	//do_action( 'woocommerce_after_shop_loop_item' );
	?>
</div>
</div>
