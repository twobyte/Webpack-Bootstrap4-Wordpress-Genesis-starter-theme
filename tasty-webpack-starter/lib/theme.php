<?php


/** Add new image sizes */
//add_image_size( $name, $width = 0, $height = 0, $crop = false );
add_image_size( 'featured', 720, 400 );
add_image_size( 'retina', 1440, 800 );
add_image_size( 'grid', 295, 100, TRUE );
add_image_size( 'portfolio', 300, 200, TRUE );

/** Unregister layout settings */
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'header-right' );


/** Add OMG logotype */
function tasty_write_logotype( $title, $inside, $wrap ) {
	$tasty_theme = wp_get_theme();
	// update logo path for your app top of functions.php
	$logoURL = LOGO_URL;
	
	$bitmapPath = $logoURL.'.png';
	$svgPath = $logoURL.'.svg';
	
	$logos = '';
	$altbitmap = '';
	
	if(file_exists(get_stylesheet_directory().$svgPath)){
		
		$xml = simplexml_load_file(get_stylesheet_directory().$svgPath);
		if($xml){
			$attr = $xml->attributes();
			$box = explode(' ', $attr->viewBox);
			$svgW = $box[2] - $box[0];
			$svgH = $box[3] - $box[1];
		}
		
		$logos .= sprintf( '<img src="%s" alt="%s" width="%s" height="%s" class="svg-img" />', get_stylesheet_directory_uri().$svgPath, get_bloginfo( 'name' ), $svgW,$svgH );
		
		$altbitmap = ' class="altbitmap"';
		
	}
	
	if(file_exists(get_stylesheet_directory().$bitmapPath)){
		$bitmapSize = getimagesize(get_stylesheet_directory().$bitmapPath);
		$logos .= sprintf( '<img src="%s" alt="%s" width="%s" height="%s" %s/>', get_stylesheet_directory_uri().$bitmapPath, get_bloginfo( 'name' ), $bitmapSize[0], $bitmapSize[1], $altbitmap );
	}
	
	if($logos === ''){
		$logos = get_bloginfo( 'name' );
		
	}
	$inside = sprintf( '<a href="%s">%s</a>', trailingslashit( home_url() ), $logos );
	//* Build the title
	//$title  = sprintf( "<{$wrap} %s>{$inside}</{$wrap}>", genesis_attr( 'site-title' ) );
	
	// Build the title.
	$title = genesis_markup( array(
		'open'    => sprintf( "<h1 %s>", genesis_attr( 'site-title' ) ),
		'close'   => "</h1>",
		'content' => $inside,
		'context' => 'site-title',
		'echo'    => false,
		'params'  => array(
			'wrap' => $wrap,
		),
	) );
	
	return $title;

	
}
add_filter('genesis_seo_title', 'tasty_write_logotype', 10, 3 );



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
	wp_dequeue_script( 'superfish' );
	wp_dequeue_script( 'superfish-args' );
}
add_action( 'wp_enqueue_scripts', 'tasty_unregister_superfish' );

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





/*
function typekit(){
	?>
<script>
  (function(d) {
    var config = {
      kitId: 'gqb1zft',
      scriptTimeout: 3000,
      async: true
    },
    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
  })(document);
</script>
<?php
}
add_action('wp_head','typekit', 0);
//*/

/* Code to Display Featured Image on top of the post *//*
	
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'genesis_do_post_image', 8 );

//*
add_action( 'genesis_entry_header', 'featured_post_image', 0 );
function featured_post_image() {

	
	if ( ! is_singular( 'post' ) )  return;
	if ( has_post_thumbnail() ) {
		echo '<div class="featured-image">';
		the_post_thumbnail('featured', array( 'class' => 'featured' ));
		echo '</div>';
	}
}
/**
 * Google analytics
 */
function ga(){ 
	$gcode = GOOGLE_API_KEY;
?>
<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', '<?php echo($gcode); ?>', 'auto');
	  ga('send', 'pageview');
	
</script>
<?php 
}
add_action( 'wp_footer', 'ga', 20);


?>