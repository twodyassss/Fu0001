<?php
/**
 * Helper functions used across the twodays.
 * @since 3.0.0
**/

 /**
 * 和 wp_is_mobile() 不同的是排除ipad
 */
if (false===function_exists('twodays_is_mobile')) {
  function twodays_is_mobile() {
    static $is_mobile;
    
    if ( isset($is_mobile) )
      return $is_mobile;

    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
      $is_mobile = false;
    } elseif (
      strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
      || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false ) {
        $is_mobile = true;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') == false) {
      $is_mobile = true;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false) {
      $is_mobile = false;
    } else {
      $is_mobile = false;
    }

    return $is_mobile;
  }
}
/**
 * Including a PHP file within the framework if the file exists.
 * 
 * @since  3.0.0
 * @param  string  $file      File path.
 * @param  bool    $internal  if false: use theme root. true, use intenally within the framework. 
 * @access public
 */
function twodays_include( $file, $internal = false ){
	global $twodays;

	/* Theme Path */
	$theme_path = trailingslashit( get_template_directory() );

	/* File Path */
	$file_path = $theme_path . $file . '.php';

	/* If internal true, use tamatebako dir. */
	if( true === $internal ){
		$file_path = trailingslashit( $theme_path . $twodays->dir ) . $file . '.php';
	}

	/* Check file exist before loading it. */
	if( file_exists( $file_path ) ) {
		include_once( $file_path );
	}
}


/**
 * Including a PHP file if a theme feature is supported and the file exists.
 *
 * @since  3.0.0
 * @param  string  $feature   Theme support feature.
 * @param  string  $file      File path relative to framework.
 * @access private
 */
function twodays_require_if_theme_supports( $feature, $file ) {
	global $twodays;

	/* Theme Dir */
	$theme_path = trailingslashit( get_template_directory() );

	/* Tamatebako Dir */
	$twodays_path = trailingslashit( $theme_path . $twodays->dir );
	
	/* File Path */
	$file_path = $twodays_path . $file . '.php';

	/* Check if feature is supported and file exist before loading it. */
	if ( current_theme_supports( $feature ) && file_exists( $file_path ) ){
		require_once( $file_path );
	}
}


/**
 * Helper Function: Get (parent) theme version
 * This function is to properly add version number to scripts and styles.
 * @since 0.1.0
 */
function twodays_theme_version(){
	$theme = wp_get_theme( get_template() );
	return $theme->get( 'Version' );
}

/**
 * Helper Function: Get (child) theme version
 * This function is to properly add version number to scripts and styles.
 * @since 0.1.0
 */
function twodays_child_theme_version(){
	if( is_child_theme() ){
		$theme = wp_get_theme( get_stylesheet() );
		return $theme->get( 'Version' );
	}
	return twodays_theme_version();
}

/**
 * Returns the (parent) theme stylesheet URI.  Will return the active theme's stylesheet URI if no child
 * theme is active. Be sure to check `is_child_theme()` when using.
 */
function twodays_get_parent_stylesheet_uri(){
	$css = twodays_theme_file( 'assets/css/style', 'css' );
	$css = $css ? $css : get_template_directory_uri() . '/style.css';
	return apply_filters( 'twodays_get_parent_stylesheet_uri', $css );
}


/**
 * Maybe Enqueue Style
 * Enqueue Style if the style is registered.
 * @return true on success or false on failure.
 */
function twodays_maybe_enqueue_style( $handle ){
	if( wp_style_is( sanitize_key( $handle ), 'registered' ) ){
		wp_enqueue_style( sanitize_key( $handle ) );
		return true;
	}
	return false;
}


/**
 * Maybe Enqueue Script
 * Enqueue Script if the script is registered.
 * @return true on success or false on failure.
 */
function twodays_maybe_enqueue_script( $handle ){
	if( wp_script_is( sanitize_key( $handle ), 'registered' ) ){
		wp_enqueue_script( sanitize_key( $handle ) );
		return true;
	}
	return false;
}

/**
 * Check Script Debug
 * Helper function to check script debug.
 */
function twodays_is_debug(){
	$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? true : false;
	return apply_filters( 'twodays_is_debug', $debug );
}


/**
 * Get parent theme assets file.
 * Return empty file not exist.
 * Also Search for minified version of the file and load it when needed.
 * @since  3.0.0
 * @param  string  $file      File path to load relative to theme directory uri.
 * @param  string  $ext       File extension, e.g "js" or "css".
 * @access public
 * @return string
 */
function twodays_theme_file( $file, $ext ){

	/* Path & URI */
	$path = trailingslashit( get_template_directory() ) . $file;
	$uri = trailingslashit( get_template_directory_uri() ) . $file;

	/* File URI */
	$file_uri = '';

	/* If "regular" file exist, use it. */
	if( file_exists(  $path . '.' . $ext ) ){
		$file_uri = $uri . '.' . $ext;
	}

	/* If not debug & min file exist, use it! */
	if( ! twodays_is_debug() && file_exists(  $path . '.min.' . $ext ) ){
		$file_uri = $uri . '.min.' . $ext;
	}

	return $file_uri;
}


/**
 * Get active theme assets file.
 * This function is created for getting child theme file.
 * Return empty if file not exist.
 * Also Search for minified version of the file and load it when needed.
 * @since  3.0.0
 * @param  string  $file      File path to load relative to child theme directory.
 * @param  string  $ext       File extension, e.g "js" or "css".
 * @access public
 * @return string
 */
function twodays_child_theme_file( $file, $ext ){

	/* Path & URI */
	$path = trailingslashit( get_stylesheet_directory() ) . $file;
	$uri = trailingslashit( get_stylesheet_directory_uri() ) . $file;

	/* File URI */
	$file_uri = '';

	/* If "regular" file exist. */
	if( file_exists(  $path . '.' . $ext ) ){
		$file_uri = $uri . '.' . $ext;
	}

	/* If not debug & min file exist, use it! */
	if( ! twodays_is_debug() && file_exists(  $path . '.min.' . $ext ) ){
		$file_uri = $uri . '.min.' . $ext;
	}

	return $file_uri;
}

/**
 * Check Minimum System Requirement.
 * @return bool
 * @since 3.0.0
 */
function twodays_minimum_requirement( $data = array() ){
	global $wp_version;

	/* if system have min req (WP & PHP), return true */
	if ( version_compare( $wp_version, $data['wp_requires'], '>=' ) && version_compare( PHP_VERSION, $data['php_requires'], '>=' ) ) {
		return true;
	}

	/* if not return false */
	return false;
}
function get_storefront_body_class( $class = '' ) {
	global $wp_query;
    $classes = array();
	 if ( ! empty( $class ) ) {
        if ( !is_array( $class ) )
            $class = preg_split( '#\s+#', $class );
        $classes = array_merge( $classes, $class );
    } else {
        // Ensure that we always coerce class to being an array.
        $class = array();
    }
    $classes = array_map( 'esc_attr', $classes );
    $classes = apply_filters( 'twodays_body_class', $classes, $class );
 
    return array_unique( $classes );
}
function twodays_body_class( $class = '' ){
	// Separates classes with a single space, collates classes for body element
	echo 'class="' . join( ' ', get_storefront_body_class( $class ) ) . '"';
}

function haguo_layouts_class( $class = '' ){
	// Separates classes with a single space, collates classes for body element
	echo 'dir="' . join( ' ', get_storefront_body_class( $class ) ) .'"';
}

function twoday_get_option( $option, $section, $default = '' ) {

        $options = get_option( $section );

        if ( isset( $options[$option] ) ) {
            return $options[$option];
        }

        return $default;
}

function twodays_get_template( $template_name, $basename = '', $t_args = array(), $echo = false ) {
	if ( ! empty( $t_args ) && is_array( $t_args ) ) {
		extract( $t_args );
	}

	$path = '';
			
	if ( $basename ) {
		// use '/' instead of "DIRECTORY_SEPARATOR", because wp_normalize_path makes the correct replace
		$array = explode( '/', wp_normalize_path( trim( $basename ) ) );
				
		$path  = $array[0];
	}
			
	$located = twodays_locate_template( $template_name, $path );
			
	if ( ! file_exists( $located ) ) {
		
		doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		
		return;
	}

	$located = apply_filters( 'um_get_template', $located, $template_name, $path, $t_args );

	ob_start();

	do_action( 'um_before_template_part', $template_name, $path, $located, $t_args );
			
	include( $located );
			
	do_action( 'member_after_template_part', $template_name, $path, $located, $t_args );
			
	$html = ob_get_clean();

	if ( ! $echo ) {
				
		return $html;
			
	} else {
				
			echo $html;
				
			return;
		}
}

/**
* Locate a template and return the path for inclusion.
*
* @access public
* @param string $template_name
* @param string $path (default: '')
* @return string
*/
function twodays_locate_template( $template_name, $path = '' ) {
	// check if there is template at theme folder	
	if ( $path ) {
		
		$template = trailingslashit( trailingslashit( module_dir_path ) . $path );
	
	} else {
		
		$template = trailingslashit( get_template_directory() );
	}
		$template .= 'templates' . DIRECTORY_SEPARATOR . $template_name;
			
	return apply_filters( 'member_locate_template', $template, $template_name, $path );
}