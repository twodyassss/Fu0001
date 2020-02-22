<?php
/**
 * Twodays functions
 * @package Twodays
 */

/**
 *
 * @return void
 */
function twodays_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'twodays_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function twodays_woocommerce_scripts() {
	wp_enqueue_style( 'twodays-woo-style', get_template_directory_uri() . '/assets/css/woocommerce.css' );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'twodays-woo-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'twodays_woocommerce_scripts' );

/*店铺链接*/
function twodays_shop_page_url() {
	echo get_permalink( wc_get_page_id( 'shop' ) );
}

/*账户链接*/
function twodays_myaccount_page_url() {
	echo get_permalink( wc_get_page_id( 'myaccount' ) );
}

/*购物车链接*/
function twodays_cart_page_url() {
	echo get_permalink( wc_get_page_id( 'cart' ) );
}

/*结账页链接*/
function twodays_checkout_page_url() {
	echo get_permalink( wc_get_page_id( 'checkout' ) );
}

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function twodays_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'twodays_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function twodays_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'twodays_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function twodays_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'twodays_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function twodays_woocommerce_loop_columns() {
	return 3;
}
add_filter( 'loop_shop_columns', 'twodays_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function twodays_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'twodays_woocommerce_related_products_args' );

if ( ! function_exists( 'twodays_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function twodays_woocommerce_product_columns_wrapper() {
		$columns = twodays_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'twodays_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'twodays_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function twodays_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'twodays_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( ! function_exists( 'twodays_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function twodays_woocommerce_wrapper_before() {
		?>
		<div class="container">
			<div class="row">
				
					<div class="col-md-8 wp-bp-content-width">
				
						<div id="primary" class="content-area">
							<main id="main" class="site-main" role="main">
								<div class="mt-3r">
		<?php
	}
}
add_action( 'woocommerce_before_main_content', 'twodays_woocommerce_wrapper_before' );

if ( ! function_exists( 'twodays_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function twodays_woocommerce_wrapper_after() {
		?>
								</div>
							</main><!-- #main -->
						</div><!-- #primary -->
					</div>
					<!-- /.col-md-8 -->

					<div class="col-md-4 wp-bp-sidebar-width">
						
						<?php get_sidebar( 'shop' ); ?>
					
					</div>
					<!-- /.col-md-4 -->		
				</div>
				<!-- /.row -->
			</div>
			<!-- /.container -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'twodays_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'twodays_woocommerce_header_cart' ) ) {
			twodays_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'twodays_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function twodays_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		twodays_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'twodays_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'twodays_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function twodays_woocommerce_cart_link() {
		?>
			<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'twodayssss' ); ?>">
				<?php /* translators: number of items in the mini cart. */ ?>
				<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'twodayssss' ), WC()->cart->get_cart_contents_count() ) );?></span>
			</a>
		<?php
	}
}

if ( ! function_exists( 'twodays_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function twodays_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php twodays_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
					$instance = array(
						'title' => '',
					);

					the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}


// Add before / after for results & sort
add_action( 'woocommerce_before_shop_loop', 'twodays_woocommerce_before_sort_result', 19 );
add_action( 'woocommerce_before_shop_loop', 'twodays_woocommerce_after_sort_result', 31 );
if ( ! function_exists( 'twodays_woocommerce_before_sort_result' ) ) {
	function twodays_woocommerce_before_sort_result() {
		?>
		<div class="d-flex justify-content-between align-items-center mb-4">
		<?php
	}
}
if ( ! function_exists( 'twodays_woocommerce_after_sort_result' ) ) {
	function twodays_woocommerce_after_sort_result() {
		?>
		</div>
		<?php
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'twodays_woocommerce_after_shop_loop_item', 6 );
if ( ! function_exists( 'twodays_woocommerce_after_shop_loop_item' ) ) {
	function twodays_woocommerce_after_shop_loop_item() {
		?>
		<br>
		<?php
	}
}

add_action( 'woocommerce_before_single_product_summary', 'twodays_before_image_gallery', 19 );
if ( ! function_exists( 'twodays_before_image_gallery' ) ) {
	function twodays_before_image_gallery() {
		?>
		<div class="row">
		<?php
	}
}

add_action( 'woocommerce_before_single_product_summary', 'twodays_before_product_summary', 21 );
if ( ! function_exists( 'twodays_before_product_summary' ) ) {
	function twodays_before_product_summary() {
		?>
		<div class="col-md-7">
		<?php
	}
}

add_action( 'woocommerce_after_single_product_summary', 'twodays_after_product_summary', 1 );
if ( ! function_exists( 'twodays_after_product_summary' ) ) {
	function twodays_after_product_summary() {
		?>
			</div><!-- /.col-md-7 -->
		</div><!-- /.row -->
		<?php
	}
}

add_filter( 'woocommerce_single_product_image_gallery_classes', 'twodays_add_product_gallery_class' );
if ( ! function_exists( 'twodays_add_product_gallery_class' ) ) {
	function twodays_add_product_gallery_class( $classes ) {
		$classes[] = 'col-md-5';
		return $classes;
	}
}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_filter('woocommerce_add_to_cart_redirect', 'twodays_redirect_to_checkout');
if ( ! function_exists( 'twodays_redirect_to_checkout' ) ) {
	function twodays_redirect_to_checkout() {
		global $woocommerce;
		$checkout_url = wc_get_checkout_url();
		return $checkout_url;
	}
}

/* 删除附加信息选项卡 */
//add_filter( 'woocommerce_product_tabs', 'twodays_remove_product_tabs', 98 );
function twodays_remove_product_tabs( $tabs ) {
unset( $tabs['additional_information'] );
return $tabs;
}
/* WooCommerce: The Code Below Removes The Additional Information Title Text */
add_filter('woocommerce_enable_order_notes_field', '__return_false');