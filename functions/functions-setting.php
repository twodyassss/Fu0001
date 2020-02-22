<?php
/**
 * Twodays functions
 *
 * @package Twodays
 */
if ( !class_exists('Twodays_Settings_API' ) ):
class Twodays_Settings_API {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_menu_page( '两天网设置 API', '两天网设置 API', 'delete_posts', 'twodays_settings_api', array($this, 'twodays_settings_page'), '', 80 );
    }

    function get_settings_sections() {
        $sections = array(
			array(
                'id' => 'twodays_modules',
                'title' => __( '扩展配置', 'twodayssss' )
            ),
            array(
                'id' => 'twodays_basics',
                'title' => __( '基本设置', 'twodayssss' )
            ),
            array(
                'id' => 'twodays_advanced',
                'title' => __( '高级设置', 'twodayssss' )
            ),
            array(
                'id' => 'twodays_others',
                'title' => __( '其他设置', 'twodayssss' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
			'twodays_modules' => array(
				array(
                    'name'  => 'member_online',
                    'label' => __( '在线状态', 'twodayssss' ),
                    'desc'  => __( '显示会员在线状态', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_followers',
                    'label' => __( '用户关注', 'twodayssss' ),
                    'desc'  => __( '用户关注', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_user_tags',
                    'label' => __( '会员标签', 'twodayssss' ),
                    'desc'  => __( '会员标签', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'  => 'member_bbpress',
                    'label' => __( 'BBS论坛', 'twodayssss' ),
                    'desc'  => __( 'BBS论坛', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),array(
                    'name'  => 'member_bookmarks',
                    'label' => __( '书签收藏', 'twodayssss' ),
                    'desc'  => __( '书签收藏', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_friends',
                    'label' => __( '好友', 'twodayssss' ),
                    'desc'  => __( '好友', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_groups',
                    'label' => __( 'groups', 'twodayssss' ),
                    'desc'  => __( 'groups', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_instagram',
                    'label' => __( 'Instagram', 'twodayssss' ),
                    'desc'  => __( 'Instagram', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_mailchimp',
                    'label' => __( '邮件营销', 'twodayssss' ),
                    'desc'  => __( '邮件营销', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_messaging',
                    'label' => __( '用户私信', 'twodayssss' ),
                    'desc'  => __( '用户私信', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_mycred',
                    'label' => __( '积分', 'twodayssss' ),
                    'desc'  => __( '积分', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_notices',
                    'label' => __( '通知', 'twodayssss' ),
                    'desc'  => __( '通知', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_notifications',
                    'label' => __( '实时通知', 'twodayssss' ),
                    'desc'  => __( '实时通知', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_private_content',
                    'label' => __( '私密内容', 'twodayssss' ),
                    'desc'  => __( '私密内容', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_profile_completeness',
                    'label' => __( '会员完善信息', 'twodayssss' ),
                    'desc'  => __( '会员完善信息', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_recaptcha',
                    'label' => __( 'Google reCAPTCHA', 'twodayssss' ),
                    'desc'  => __( 'Google reCAPTCHA 验证码', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_reviews',
                    'label' => __( '会员评级', 'twodayssss' ),
                    'desc'  => __( '会员评级', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_social_activity',
                    'label' => __( '社交活动', 'twodayssss' ),
                    'desc'  => __( '社交活动', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_social_login',
                    'label' => __( '社交登录', 'twodayssss' ),
                    'desc'  => __( '社交登录', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_terms_conditions',
                    'label' => __( '条款及细则', 'twodayssss' ),
                    'desc'  => __( '条款及细则', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_user_photos',
                    'label' => __( '会员相册', 'twodayssss' ),
                    'desc'  => __( '会员相册', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_verified_users',
                    'label' => __( '会员认证', 'twodayssss' ),
                    'desc'  => __( '会员认证', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
				array(
                    'name'  => 'member_woocommerce',
                    'label' => __( '商店集成', 'twodayssss' ),
                    'desc'  => __( '商店集成-woocommerce', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'  => 'member_switcher',
                    'label' => __( 'SW切换', 'twodayssss' ),
                    'desc'  => __( 'switcher角色切换', 'twodayssss' ),
                    'type'  => 'checkbox'
                )
            ),
            'twodays_basics' => array(
                array(
                    'name'              => 'text_val',
                    'label'             => __( 'Text Input', 'twodayssss' ),
                    'desc'              => __( 'Text input description', 'twodayssss' ),
                    'type'              => 'text',
                    'default'           => 'Title',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'              => 'number_input',
                    'label'             => __( 'Number Input', 'twodayssss' ),
                    'desc'              => __( 'Number field with validation callback `intval`', 'twodayssss' ),
                    'type'              => 'number',
                    'default'           => 'Title',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name'  => 'textarea',
                    'label' => __( 'Textarea Input', 'twodayssss' ),
                    'desc'  => __( 'Textarea description', 'twodayssss' ),
                    'type'  => 'textarea'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'twodayssss' ),
                    'desc'  => __( 'Checkbox Label', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'twodayssss' ),
                    'desc'    => __( 'A radio button', 'twodayssss' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'twodayssss' ),
                    'desc'    => __( 'Multi checkbox description', 'twodayssss' ),
                    'type'    => 'multicheck',
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'twodayssss' ),
                    'desc'    => __( 'Dropdown description', 'twodayssss' ),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'twodayssss' ),
                    'desc'    => __( 'Password description', 'twodayssss' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'twodayssss' ),
                    'desc'    => __( 'File description', 'twodayssss' ),
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                )
            ),
            'twodays_advanced' => array(
                array(
                    'name'    => 'color',
                    'label'   => __( 'Color', 'twodayssss' ),
                    'desc'    => __( 'Color description', 'twodayssss' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'twodayssss' ),
                    'desc'    => __( 'Password description', 'twodayssss' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'wysiwyg',
                    'label'   => __( 'Advanced Editor', 'twodayssss' ),
                    'desc'    => __( 'WP_Editor description', 'twodayssss' ),
                    'type'    => 'wysiwyg',
                    'default' => ''
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'twodayssss' ),
                    'desc'    => __( 'Multi checkbox description', 'twodayssss' ),
                    'type'    => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'twodayssss' ),
                    'desc'    => __( 'Dropdown description', 'twodayssss' ),
                    'type'    => 'select',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'twodayssss' ),
                    'desc'    => __( 'Password description', 'twodayssss' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'twodayssss' ),
                    'desc'    => __( 'File description', 'twodayssss' ),
                    'type'    => 'file',
                    'default' => ''
                )
            ),
            'twodays_others' => array(
                array(
                    'name'    => 'text',
                    'label'   => __( 'Text Input', 'twodayssss' ),
                    'desc'    => __( 'Text input description', 'twodayssss' ),
                    'type'    => 'text',
                    'default' => 'Title'
                ),
                array(
                    'name'  => 'textarea',
                    'label' => __( 'Textarea Input', 'twodayssss' ),
                    'desc'  => __( 'Textarea description', 'twodayssss' ),
                    'type'  => 'textarea'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'twodayssss' ),
                    'desc'  => __( 'Checkbox Label', 'twodayssss' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'twodayssss' ),
                    'desc'    => __( 'A radio button', 'twodayssss' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'twodayssss' ),
                    'desc'    => __( 'Multi checkbox description', 'twodayssss' ),
                    'type'    => 'multicheck',
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'twodayssss' ),
                    'desc'    => __( 'Dropdown description', 'twodayssss' ),
                    'type'    => 'select',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'twodayssss' ),
                    'desc'    => __( 'Password description', 'twodayssss' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'twodayssss' ),
                    'desc'    => __( 'File description', 'twodayssss' ),
                    'type'    => 'file',
                    'default' => ''
                )
            )
        );

        return $settings_fields;
    }

    function twodays_settings_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;
new Twodays_Settings_API;