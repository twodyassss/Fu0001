<?php
/**
 * Twodays functions
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * <?php the_header_image_tag(); ?>
 * @uses twodays_header_style()
 * @package Twodays
 */

/**
 * Set up the theme core custom header feature.
 */
function twodays_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'twodays_custom_header_args', array(
		'default-image'          => get_template_directory_uri() . '/assets/images/default-cover-img.jpeg',
		'default-text-color'     => 'ffffff',
		'width'                  => 1440,
		'height'                 => 500,
		'flex-height'            => true,
		'flex-width'             => true,
		'wp-head-callback'       => 'twodays_header_style',
	) ) );

	register_default_headers( array(
		'desk' => array(
			'url'           => '%s/assets/images/default-cover-img.jpeg',
			'thumbnail_url' => '%s/assets/images/default-cover-img.jpeg',
			'description'   => __( 'Desk', 'twodayssss' )
		),
	) );
}
add_action( 'after_setup_theme', 'twodays_custom_header_setup' );

if ( ! function_exists( 'twodays_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see twodays_custom_header_setup().
	 */
	function twodays_header_style() {

		if ( get_header_image() ) : ?>
			<style type="text/css">
				.home .wp-bs-4-jumbotron {
					background-image: url(<?php echo esc_url( get_header_image() ); ?>);
					padding: 0;
					background-size: cover;
					background-position: center;
					border-radius: 0;
				}
				.wp-bp-jumbo-overlay {
					background: rgba(33,37,41, 0.7);
					padding: 10rem 2rem;
				}
			</style>
		<?php
		endif;

		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
		?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
		<?php
			// If the user has set a custom color for the text use that.
			else :
		?>
			.site-title a,
			.navbar-dark .navbar-brand,
			.navbar-dark .navbar-nav .nav-link,
			.navbar-dark .navbar-nav .nav-link:hover, .navbar-dark .navbar-nav .nav-link:focus,
			.navbar-dark .navbar-brand:hover, .navbar-dark .navbar-brand:focus,
			.navbar-dark .navbar-nav .show > .nav-link, .navbar-dark .navbar-nav .active > .nav-link, .navbar-dark .navbar-nav .nav-link.show, .navbar-dark .navbar-nav .nav-link.active,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;
add_action( 'wp_head', 'twodays_header_style' );