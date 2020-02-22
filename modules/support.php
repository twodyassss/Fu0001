<?php
/**
 * Support functions used across the twodays.
 * @since 3.0.0
**/

$options_online 			  = twoday_get_option( 'member_online','twodays_modules' );

$options_user_tags 			  = twoday_get_option( 'member_user_tags','twodays_modules' );

$options_followers 			  = twoday_get_option( 'member_followers','twodays_modules' );

$options_woocommerce 		  = twoday_get_option( 'member_woocommerce','twodays_modules', );

$options_mycred 			  = twoday_get_option( 'member_mycred','twodays_modules' );

$options_private_content 	  = twoday_get_option( 'member_private_content','twodays_modules' );

$options_profile_completeness = twoday_get_option( 'member_profile_completeness','twodays_modules' );

$options_messaging			  = twoday_get_option( 'member_messaging','twodays_modules' );

$options_bookmarks			  = twoday_get_option( 'member_bookmarks','twodays_modules' );

$options_verified_users		  = twoday_get_option( 'member_verified_users','twodays_modules' );

$options_friends			  = twoday_get_option( 'member_friends','twodays_modules' );

$options_groups				  = twoday_get_option( 'member_groups','twodays_modules' );

$options_notices			  = twoday_get_option( 'member_notices','twodays_modules', );

$options_user_photos		  = twoday_get_option( 'member_user_photos','twodays_modules' );

$options_reviews			  = twoday_get_option( 'member_reviews','twodays_modules' );

$options_terms_conditions	  = twoday_get_option( 'member_terms_conditions','twodays_modules' );

$options_social_activity	  = twoday_get_option( 'member_social_activity','twodays_modules' );

$options_recaptcha			  = twoday_get_option( 'member_recaptcha','twodays_modules' );

$options_social_login		  = twoday_get_option( 'member_social_login','twodays_modules' );

$options_instagram			  = twoday_get_option( 'member_instagram','twodays_modules' );

$options_notifications		  = twoday_get_option( 'member_notifications','twodays_modules' );

$options_bbpress			  = twoday_get_option( 'member_bbpress','twodays_modules' );

$options_mailchimp			  = twoday_get_option( 'member_mailchimp','twodays_modules' );

$options_switcher			  = twoday_get_option( 'member_switcher','twodays_modules' );


if($options_online=='on')
	add_theme_support( 'member-online' );

if($options_user_tags=='on')
	add_theme_support( 'member-user-tags' );

if($options_followers=='on')
	add_theme_support( 'member-followers' );

if($options_woocommerce=='on' && class_exists('WooCommerce'))
	add_theme_support( 'member-woocommerce' );

if($options_mycred=='on')
	add_theme_support( 'member-mycred' );

if($options_private_content=='on')
	add_theme_support( 'member-private-content' );

if($options_profile_completeness=='on')
	add_theme_support( 'member-profile-completeness' );

if($options_messaging=='on')
	add_theme_support( 'member-messaging' );

if($options_bookmarks=='on')
	add_theme_support( 'member-bookmarks' );

if($options_verified_users=='on')
	add_theme_support( 'member-verified-users' );

if($options_friends=='on')
	add_theme_support( 'member-friends' );

if($options_groups=='on')
	add_theme_support( 'member-groups' );

if($options_notices=='on')
	add_theme_support( 'member-notices' );

if($options_user_photos=='on')
	add_theme_support( 'member-user-photos' );

if($options_reviews=='on')
	add_theme_support( 'member-reviews' );

if($options_terms_conditions=='on')
	add_theme_support( 'member-terms-conditions' );

if($options_social_activity=='on')
	add_theme_support( 'member-social-activity' );

if($options_recaptcha=='on')
	add_theme_support( 'member-recaptcha' );

if($options_social_login=='on')
	add_theme_support( 'member-social-login' );

if($options_instagram=='on')
	add_theme_support( 'member-instagram' );

if($options_notifications=='on')
	add_theme_support( 'member-notifications' );

if($options_bbpress=='on')
	add_theme_support( 'member-bbpress' );

if($options_mailchimp=='on')
	add_theme_support( 'member-mailchimp' );

if($options_switcher=='on')
	add_theme_support( 'member-switcher' );