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

		$svg = file_get_contents(get_stylesheet_directory().$svgPath);
		$logos .= '<!--[if gte IE 9]><!-->'; // needed to not choke up IE8
		$logos .= ( isset($svgW) && isset($svgH) && ($svgW*$svgH>0) ) ? sprintf('<div class="svg-logo" style="width:%spx;height:%spx">',$svgW,$svgH) : '';
		$logos .= $svg;
		$logos .= ( isset($svgW) && isset($svgH) && ($svgW*$svgH>0) ) ? '</div>' : '';
		$logos .= '<!--<![endif]-->';
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