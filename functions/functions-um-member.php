<?php
/**
 * get member searchform.
 */
function twodays_get_member_searchform() {
	// Hide for logged ultimatemember searchform
	if ( is_user_logged_in() ) {
		echo do_shortcode( '[ultimatemember_searchform]' );
	} else {
		get_search_form();
	}
}

function twodays_frontpage_template_login() {

	if ( !is_user_logged_in() ) {
		echo '<div class="card-body card">';
		echo '<header class="entry-header">';
		echo '<h1 class="entry-title h2" style="text-align: center;">登录</h1>';		
		echo '</header><!-- .entry-header -->';
		echo '<div class="entry-content">';	
		echo do_shortcode( '[ultimatemember form_id="6"]' );
		echo '</div><!-- .entry-content -->';
		echo '</div>';	
	} else {
		echo '';
	}
}

add_filter('um_account_page_default_tabs_hook', 'twodays_custom_tab_in_member', 100 );
function twodays_custom_tab_in_member( $tabs ) {
	$tabs[999999]['logout']['icon'] = 'um-faicon-sign-out';
	$tabs[999999]['logout']['title'] = '退出登录';
	$tabs[999999]['logout']['custom'] = true;
	return $tabs;
}
	
/* make our new tab hookable */

add_action('um_account_tab__logout', 'twodays_um_account_tab_logout');
function twodays_um_account_tab_logout( $info ) {
	global $ultimatemember;
	extract( $info );

	$output = $ultimatemember->account->get_tab_output('logout');
	if ( $output ) { echo $output; }
}

/* Finally we add some content in the tab */

add_filter('um_account_content_hook_logout', 'twodays_um_account_content_hook_logout');
function twodays_um_account_content_hook_logout( $output ){
	ob_start();
	
	?>		
	<div class="um-field">
		
		<a class="button" type="submit" href="<?php echo wp_logout_url( home_url());?>" aria-label="退出登录 Varia">退出登录</a>
		
	</div>	
	<?php	
	$output .= ob_get_contents();
	ob_end_clean();
	return $output;
}