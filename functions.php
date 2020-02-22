<?php

/**
 * twodayssss Twodays functions and definitions
 *
 * @package Twodays
 */
if (!function_exists('twodays_setup')) :
	function twodays_setup()
	{

		// Make theme available for translation.
		load_theme_textdomain('twodayssss', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		// Let manage the document title.
		add_theme_support('title-tag');

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support('post-thumbnails');

		// Enable Post formats
		add_theme_support('post-formats', array('gallery', 'video', 'audio', 'status', 'quote', 'link'));

		// Enable support for woocommerce.
		add_theme_support('woocommerce');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => esc_html__('Primary', 'twodayssss'),
			'second'  => esc_html__('Tourist Menu', 'twodayssss'),
			'footer'  => esc_html__('Footer Menu', 'twodayssss'),
		));

		// Switch default core markup for search form, comment form, and comments
		add_theme_support('html5', array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		));

		// Set up the core custom background feature.
		add_theme_support('custom-background', apply_filters('twodays_custom_background_args', array(
			'default-color' => 'f8f9fa',
			'default-image' => '',
		)));

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		// Add support for core custom logo.
		add_theme_support('custom-logo', array(
			'height'      => 60,
			'width'       => 'auto',
			'flex-width'  => true,
			'flex-height' => true,
		));
	}
endif;
add_action('after_setup_theme', 'twodays_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function twodays_content_width()
{
	$GLOBALS['content_width'] = apply_filters('twodays_content_width', 800);
}
add_action('after_setup_theme', 'twodays_content_width', 0);

/**
 * Register widget area.
 */
function twodays_widgets_init()
{
	register_sidebar(array(
		'name'          => esc_html__('Sidebar', 'twodayssss'),
		'id'            => 'sidebar-1',
		'description'   => esc_html__('Add widgets here.', 'twodayssss'),
		'before_widget' => '<section id="%1$s" class="widget border-bottom %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title h6">',
		'after_title'   => '</h5>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Sidebar tag', 'twodayssss'),
		'id'            => 'sidebar-tag',
		'description'   => esc_html__('Add widgets here.', 'twodayssss'),
		'before_widget' => '<section id="%1$s" class="widget border-bottom %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title h6">',
		'after_title'   => '</h5>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Footer Column 1', 'twodayssss'),
		'id'            => 'footer-1',
		'description'   => esc_html__('Add widgets here.', 'twodayssss'),
		'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title h6">',
		'after_title'   => '</h5>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Footer Column 2', 'twodayssss'),
		'id'            => 'footer-2',
		'description'   => esc_html__('Add widgets here.', 'twodayssss'),
		'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title h6">',
		'after_title'   => '</h5>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Footer Column 3', 'twodayssss'),
		'id'            => 'footer-3',
		'description'   => esc_html__('Add widgets here.', 'twodayssss'),
		'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title h6">',
		'after_title'   => '</h5>',
	));

	register_sidebar(array(
		'name'          => esc_html__('Footer Column 4', 'twodayssss'),
		'id'            => 'footer-4',
		'description'   => esc_html__('Add widgets here.', 'twodayssss'),
		'before_widget' => '<section id="%1$s" class="widget wp-bp-footer-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h5 class="widget-title h6">',
		'after_title'   => '</h5>',
	));
}
add_action('widgets_init', 'twodays_widgets_init');

/**
 * Enqueue scripts and styles.
 */

function twodays_scripts()
{
	global $wp_styles, $pagenow, $wp_query;

	$theme_version = wp_get_theme()->get('Version');
	$bootstrap_version = 'v4.0.0';
	$style_version = filemtime(TEMPLATEPATH . '/assets/css/twodays-style.css');

	//$jquery_version = filemtime(TEMPLATEPATH . '/assets/js/twodays.js');
	wp_enqueue_style('open-iconic-bootstrap', get_template_directory_uri() . '/assets/css/open-iconic-bootstrap.css', array(), $bootstrap_version, 'all');
	wp_enqueue_style('twodays', get_template_directory_uri() . '/assets/css/bootstrap.css', array(), $bootstrap_version, 'all');
	wp_enqueue_style('style', get_stylesheet_uri(), array(), $theme_version, 'all');
	wp_enqueue_style('twodays-style', get_template_directory_uri() . '/assets/css/twodays-style.css', array(), $style_version, 'all');
	wp_enqueue_script('twodays', get_template_directory_uri() . '/assets/js/bootstrap.js', array('jquery'), $bootstrap_version, true);
	//wp_enqueue_script( 'twodays-js', get_template_directory_uri() . '/assets/js/twodays.js', array('jquery'), $jquery_version, true );

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	wp_localize_script('twodays', 'twodays', array(
		'ajax_url' => admin_url('admin-ajax.php'),
	));
	$custom_css = " ";
	wp_add_inline_style('twodays', $custom_css);
}
add_action('wp_enqueue_scripts', 'twodays_scripts');

/**
 * Registers an editor stylesheet for the theme.
 */
function twodays_add_editor_styles()
{
	add_editor_style('editor-style.css');
}
add_action('admin_init', 'twodays_add_editor_styles');

/* 加载设置字段模块. */
require_once(trailingslashit(get_template_directory()) . 'modules/admin/class.settings-api.php');

require_once(trailingslashit(get_template_directory()) . 'modules/admin/class.meta-box-api.php');

// Customizer 扩展设置.
require get_template_directory() . '/functions/functions-setting.php';

// Customizer 扩展函数.
require get_template_directory() . '/functions/functions-core.php';

// Customizer 扩展会员功能函数.
require get_template_directory() . '/functions/functions-um-member.php';

// Custom 函数
require get_template_directory() . '/inc/admin/admin-init.php';

// Implement the Custom Header feature.
require get_template_directory() . '/inc/custom-header.php';

// Implement the Custom Comment feature.
require get_template_directory() . '/inc/custom-comment.php';

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Functions which enhance the theme by hooking into theme.
require get_template_directory() . '/inc/template-functions.php';

// Custom Navbar
require get_template_directory() . '/inc/custom-navbar.php';

// Customizer additions.
require get_template_directory() . '/inc/customizer.php';

// Load Jetpack compatibility file.
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Load WooCommerce compatibility file.
if (class_exists('WooCommerce')) {
	require get_template_directory() . '/inc/woocommerce.php';
}

//引入核心扩展模块.
require get_template_directory() . '/modules/module.php';
