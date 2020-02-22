<?php
/**
 * module config functions (扩展功能函数入口)
 */
/*
 * Settings API wrapper class--后台设置类
 * @version 1.2
 */

define( 'module_dir_path',__DIR__ );

global $twodays;
$twodays = new stdClass;

/* Add theme name in the object. */
$twodays->name = esc_attr( get_template() );
$twodays->child = esc_attr( get_stylesheet() );


/* Add twodays directory/folder (not path) in the object. */
$twodays->dir = basename( dirname( __FILE__ ) );

/* === LOAD FUNCTIONS === */
//var_dump(trailingslashit( module_dir_path ));
/* Load File Loader. */
require_once( trailingslashit( get_template_directory() ) . $twodays->dir . '/helper.php' );
/* 加载设置字段模块. */
require_once( trailingslashit( get_template_directory() ) . $twodays->dir . '/support.php' );

/* Load custom theme features. */
add_action( 'after_setup_theme', 'twoday_load_option_support', 15 );

/**
 * Load various modules only when theme add supports for it.
 * 
 * @since 3.0.0
 * @return void
 */
function twoday_load_option_support(){
	/* 加载在线状态模块. */
	twodays_require_if_theme_supports( 'member-online', 'member-online/member-online' );
	/* 加载用户标签模块*/
	twodays_require_if_theme_supports( 'member-user-tags', 'member-user-tags/member-user-tags' );
	/* 加载关注用户模块*/
	twodays_require_if_theme_supports( 'member-followers', 'member-followers/member-followers' );
	/* 加载商店模块*/
	twodays_require_if_theme_supports( 'member-woocommerce', 'member-woocommerce/member-woocommerce' );
	/*加载积分模块*/
	twodays_require_if_theme_supports( 'member-mycred', 'mycred/mycred' );
	twodays_require_if_theme_supports( 'member-mycred', 'member-mycred/member-mycred' );
	/* 加载私密内容模块 */
	twodays_require_if_theme_supports( 'member-private-content', 'member-private-content/member-private-content' );
	/* 加载个人中心完成资料进度*/
	twodays_require_if_theme_supports( 'member-profile-completeness', 'member-profile-completeness/member-profile-completeness' );
	/* 加载私信模块*/
	twodays_require_if_theme_supports( 'member-messaging', 'member-messaging/member-messaging' );
	/* 加载用户书签模块*/
	twodays_require_if_theme_supports( 'member-bookmarks', 'member-bookmarks/member-user-bookmarks' );
	/* 加载验证用户模块*/
	twodays_require_if_theme_supports( 'member-verified-users', 'member-verified-users/member-verified-users' );
	/* 加载添加好友模块*/
	twodays_require_if_theme_supports( 'member-friends', 'member-friends/member-friends' );
	/* 加载添创建群组模块*/
	twodays_require_if_theme_supports( 'member-groups', 'member-groups/member-groups' );
	/* 加载添加通知模块*/
	twodays_require_if_theme_supports( 'member-notices', 'member-notices/member-notices' );
	/* 加载添加好友模块*/
	twodays_require_if_theme_supports( 'member-user-photos', 'member-user-photos/member-user-photos' );
	/* 加载用户评级模块*/
	twodays_require_if_theme_supports( 'member-reviews', 'member-reviews/member-reviews' );
	/* 加载条款及细则模块*/
	twodays_require_if_theme_supports( 'member-terms-conditions', 'member-terms-conditions/member-terms-conditions' );
	/* 加载社交活动模块*/
	twodays_require_if_theme_supports( 'member-social-activity', 'member-social-activity/member-social-activity' );
	/* 加载Google reCAPTCHA 验证码模块*/
	twodays_require_if_theme_supports( 'member-recaptcha', 'member-recaptcha/member-recaptcha' );
	/* 加载社交登录模块*/
	twodays_require_if_theme_supports( 'member-social-login', 'member-social-login/member-social-login' );
	/* 加载社交信息流模块*/
	twodays_require_if_theme_supports( 'member-instagram', 'member-instagram/member-instagram' );
	/* 加载实时通知模块*/
	twodays_require_if_theme_supports( 'member-notifications', 'member-notifications/member-notifications' );
	/* 加载论坛模块*/
	//twodays_require_if_theme_supports( 'member-bbpress', 'member-bbpress/member-bbpress' );
	/* 加载邮件营销模块*/
	twodays_require_if_theme_supports( 'member-mailchimp', 'member-mailchimp/member-mailchimp' );
	/* 加载付费切换用户角色模块*/
	twodays_require_if_theme_supports( 'member-switcher', 'member-switcher/member-switcher' );
}

add_action('admin_enqueue_scripts', 'twoday_admin_enqueue_scripts', 20);
function twoday_admin_enqueue_scripts() {
	
	wp_enqueue_style( 'twodays-admin', get_template_directory_uri() . '/assets/css/twodays-admin.css', array(), 'v1.0.0', 'all' );

}
	