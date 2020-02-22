<?php
if ( ! defined( 'ABSPATH' ) ) exit;


	/***
	***	@extend core pages
	***/
	add_filter("um_core_pages", 'um_activity_core_page' );
	function um_activity_core_page( $pages ) {
		$pages['activity'] = array( 'title' => __( 'Activity', 'twodayssss' ) );
		return $pages;
	}
	
	/***
	***	@extend settings
	***/
add_filter( 'um_settings_structure', 'um_activity_settings', 10, 1 );

function um_activity_settings( $settings ) {

    $key = ! empty( $settings['extensions']['sections'] ) ? 'activity' : '';
    $settings['extensions']['sections'][$key] = array(
        'title'     => __( 'Social Activity','twodayssss'),
        'fields'    => array(
            array(
                'id'       => 'activity_posts_num',
                'type'     => 'text',
                'label'    => __( 'Number of wall posts on desktop','twodayssss' ),
                'size' => 'small'
            ),
            array(
                'id'       => 'activity_max_faces',
                'type'     => 'text',
                'label'    => __( 'Maximum number of faces','twodayssss' ),
                'size' => 'small'
            ),
            array(
                'id'       => 'activity_posts_num_mob',
                'type'     => 'text',
                'label'    => __( 'Number of wall posts on mobile','twodayssss' ),
                'size' => 'small'
            ),
            array(
                'id'       => 'activity_init_comments_count',
                'type'     => 'text',
                'label'    => __( 'Number of initial comments/replies to display per post','twodayssss' ),
                'size' => 'small'
            ),
            array(
                'id'       => 'activity_load_comments_count',
                'type'     => 'text',
                'label'    => __( 'Number of comments/replies to get when user load more','twodayssss' ),
                'size' => 'small'
            ),
            array(
                'id'       	=> 'activity_order_comment',
                'type'     	=> 'select',
                'label'    	=> __( 'Comments order','twodayssss' ),
                'options' 	=> array(
                    'desc' 		=> __('Newest first','twodayssss'),
                    'asc' 		=> __('Oldest first','twodayssss'),
                ),
                'placeholder'=> __('Select...','twodayssss'),
                'size' => 'small'
            ),
            array(
                'id'       => 'activity_post_truncate',
                'type'     => 'text',
                'label'    => __( 'How many words appear before wall post is truncated?','twodayssss' ),
                'size' => 'small'

            ),
            array(
                'id'       	=> 'activity_enable_privacy',
                'type'     	=> 'checkbox',
                'label'   	=> __( 'Allow users to set their activity wall privacy through account page?','twodayssss'),
            ),
            array(
                'id'       => 'activity_trending_days',
                'type'     => 'text',
                'label'	   => __('Trending Hashtags Days','twodayssss'),
                'desc'     => __( 'Enter number of days here. For example: 0 will calculate trending hashtags over today only, and 7 will calculate trending hashtags over a 7 day period.','twodayssss' ),
                'size' => 'small',
            ),
            array(
                'id'       	=> 'activity_require_login',
                'type'     	=> 'checkbox',
                'label'   	=> __( 'Require user to login to view activity walls?','twodayssss'),
            ),
            array(
                'id'       => 'activity_need_to_login',
                'type'     => 'textarea',
                'label'    => __( 'Text to display If user needs to login to interact in a post','twodayssss' ),
                'rows'	   => 2,
            ),
            array(
                'id'       		=> 'activity_highlight_color',
                'type'     		=> 'color',
                'label'    		=> __( 'Active Color','twodayssss' ),
                'validate' 		=> 'color',
                'transparent'	=> false,
            )
        )
    );


    $settings = apply_filters( 'um_activity_settings_structure', $settings, $key );

    foreach( apply_filters( 'um_activity_global_actions', UM()->Activity_API()->api()->global_actions ) as $k => $v ) {
        if ( $k == 'status' )
            continue;

        $settings['extensions']['sections'][$key]['fields'] = array_merge($settings['extensions']['sections'][$key]['fields'], array(
            array(
                'id'        => 'activity-' . $k,
                'type'     	=> 'checkbox',
                'label'   	=> sprintf( __( 'Enable "%s" in activity', 'twodayssss' ), $v ),
            )
        ) );
    }

    return $settings;
}

	/***
	***	@enable image upload
	***/
	add_filter('um_custom_image_handle_wall_img_upload', 'um_custom_image_handle_wall_img_upload');
	function um_custom_image_handle_wall_img_upload( $data ) {
		$data = array(
			'role' => 'wall-upload'
		);
		return $data;
	}
	
	/***
	***	@exclude from comments tab
	***/
	add_filter('um_excluded_comment_types', 'um_activity_excluded_comment_types' );
	function um_activity_excluded_comment_types( $array ) {
		$array[] = 'um-social-activity';
		return $array;
	}