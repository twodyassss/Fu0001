<?php
/**
 * Twodays functions
 *
 * @package Twodays
 */
add_filter( 'use_block_editor_for_post', '__return_false' );
remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

add_filter( 'get_avatar', function ($avatar) {
		return str_replace( array('cn.gravatar.com/avatar', 'secure.gravatar.com/avatar', '0.gravatar.com/avatar', '1.gravatar.com/avatar', '2.gravatar.com/avatar'), 'cdn.v2ex.com/gravatar', $avatar );
}, 10, 3 );

/**
 * 
 */
function twodays_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}

function twodays_add_logout_link( $items, $args ) { 
    if ( $args->theme_location == 'primary' ) {
		ob_start();
		?>
			
			<li class="nav-item menu-item"><a class="nav-link" href="<?php echo  esc_url( wp_logout_url( $_SERVER['REQUEST_URI'] ) );?>">退出</a></li>
		
		<?php		
			$output .= ob_get_contents();
		ob_end_clean();	
		$items.= $output; 
	}
	return $items;
} 
add_filter( 'wp_nav_menu_items', 'twodays_add_logout_link', 10, 2 );
