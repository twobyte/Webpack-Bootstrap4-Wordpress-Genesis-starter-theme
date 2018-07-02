<?php
/**
 * Tasty Webpack Starter.
 *
 * This is child to the Genesis Theme by StudioPress.
 *
 * @package Tasty Webpack Starter
 * @author  TastyDigital
 * @license GPL-2.0+
 * @link    https://tastydigital.com/themes/tasty-webpack-starter/
 */
 
// props to [Brian Gardner and the CopyBlogger team] (https://github.com/copyblogger/genesis-sample)

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', 'Tasty Webpack Starter' );
define( 'CHILD_THEME_URL', 'http://tastydigital.com/' );
define( 'CHILD_THEME_VERSION', '2.0.0' );

// update app settings
define( 'LOGO_URL', '/assets/images/TastyDigital-logo');
define( 'FAVICON_URL', '/assets/images/favicon.ico');
define( 'GOOGLE_API_KEY', 'UA-XXXXXXX-X');
	
// secret sauce to support webpack dev server...
remove_action( 'wp_enqueue_scripts', 'genesis_enqueue_main_stylesheet', 5 );
require __DIR__ . '/webpack-wp-scripts.php';
function webpack_enqueue_assets() {
	// add script dependencies
	$opts = [
		'scripts'  => ['jquery']
	];
	// In a theme, pass in the stylesheet directory:
	\tastyWebpack\enqueue_assets( get_stylesheet_directory(), $opts );

	// In a plugin, pass the plugin dir path:
	//\tastyWebpack\enqueue_assets( plugin_dir_path( __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'webpack_enqueue_assets', 5 );


/** Start the engine */
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );


//* Set Localization (do not remove)
load_child_theme_textdomain( 'tasty-starter', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'tasty-starter' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Include Customizer CSS
include_once( get_stylesheet_directory() . '/lib/output.php' );


function tasty_theme_add_editor_styles() {
	add_editor_style( 'editor-style.css' );
}
add_action( 'admin_init', 'tasty_theme_add_editor_styles' );


// Clean up Head
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3);


//* Disable any and all mention of emoji's
//* Source code credit: http://ottopress.com/
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );   
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );     
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );



// Structural Wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	//'subnav',
	'site-inner',
	'footer-widgets',
	'footer'
) );


//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

/** Add Viewport meta tag for mobile browsers //*/
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom header
/*add_theme_support( 'custom-header', array(
	'width'           => 600,
	'height'          => 160,
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'flex-height'     => true,
) );*/

//* Add support for custom background
//add_theme_support( 'custom-background' );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 4 );
//* Rename primary and secondary navigation menus
add_theme_support( 'genesis-menus' , array( 'primary' => __( 'After Header Menu', 'genesis-sample' ), 'secondary' => __( 'Footer Menu', 'genesis-sample' ) ) );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 5 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' != $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;

	return $args;

}

//* Modify size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

//* Modify size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;

	return $args;

}


// Remove Edit link
add_filter( 'genesis_edit_post_link', '__return_false' );


add_filter('genesis_footer_creds_text', 'tasty_footer_creds_filter');
function tasty_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright year=2016] '.get_bloginfo ('name').' &middot; Website by <a href="http://tastydigital.com/" rel="Web designer">Tasty Digital</a>';
	return $creds;
}


add_filter('language_attributes', 'modernizr_class');
function modernizr_class($output) {
    return $output . ' class="no-js"';
}



/**
 * Remove default link for images
 */
function tasty_imagelink_setup() {
	$image_set = get_option( 'image_default_link_type' );
	if ($image_set !== 'none') {
		update_option( 'image_default_link_type', 'none' );
	}
}
add_action( 'admin_init', 'tasty_imagelink_setup', 10 );

/**
 * Remove Query Strings From Static Resources
 */
function tasty_remove_script_version( $src ){
	$parts = explode( '?ver', $src );
	return $parts[0];
}	
add_filter( 'script_loader_src', 'tasty_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'tasty_remove_script_version', 15, 1 );


/**
 * Remove Read More Jump
 */
function tasty_remove_more_jump_link( $link ) {
	$offset = strpos( $link, '#more-' );
	if ($offset) {
		$end = strpos( $link, '"',$offset );
	}
	if ($end) {
		$link = substr_replace( $link, '', $offset, $end-$offset );
	}
	return $link;
}
add_filter( 'the_content_more_link', 'tasty_remove_more_jump_link' );


/****************************************
Misc Theme Functions
*****************************************/

/**
 * Unregister the superfish scripts
 */
function tasty_unregister_superfish() {
	wp_deregister_script( 'superfish' );
	wp_deregister_script( 'superfish-args' );
}
add_action( 'custom_disable_superfish', 'tasty_unregister_superfish' );

/**
 * Filter Yoast SEO Metabox Priority
 */
function tasty_filter_yoast_seo_metabox() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'tasty_filter_yoast_seo_metabox' );

/** Adding custom Favicon */
add_filter( 'genesis_pre_load_favicon', 'tasty_favicon' );
function tasty_favicon( $favicon_url ) {
	if(file_exists(get_stylesheet_directory() . FAVICON_URL)){
		return get_stylesheet_directory_uri() . FAVICON_URL;
	}else{
		return $favicon_url;
	}
}


//* Remove 'Editor' from 'Appearance' Menu. 
//* This stops users and hackers from being able to edit files from within WordPress.  
define( 'DISALLOW_FILE_EDIT', true );


// don’t load WPML CSS
define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
define('ICL_DONT_LOAD_LANGUAGES_JS', true);


/****************************************
Theme Views
*****************************************/

include_once( CHILD_DIR . '/lib/theme.php' );


