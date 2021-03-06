<?php
/**
 * Entrypoint for the theme.
 */

namespace tastyWebpack;

/**
 * Is this a development environment?
 *
 * @return bool
 */
function is_development() {
	return apply_filters( 'reactwpscripts.is_development', WP_DEBUG );
}

/**
 * Attempt to load a file at the specified path and parse its contents as JSON.
 *
 * @param string $path The path to the JSON file to load.
 * @return array|null;
 */
function load_asset_file( $path ) {
	if ( ! file_exists( $path ) ) {
		return null;
	}
	$contents = file_get_contents( $path );
	if ( empty( $contents ) ) {
		return null;
	}
	return json_decode( $contents, true );
}

/**
 * Check a directory for a root or build asset manifest file, and attempt to
 * decode and return the asset list JSON if found.
 *
 * @param string $directory Root directory containing `src` and `build` directory.
 * @return array|null;
 */
function get_assets_list( string $directory ) {
	
	$directory = trailingslashit( $directory );
	
	$dev_assets = load_asset_file( $directory . 'asset-manifest.json' );
	
	if ( ! empty( $dev_assets ) ) {
		return array_values( $dev_assets );
	}
	return null;
}

/**
 * Infer a base web URL for a file system path.
 *
 * @param string $path Filesystem path for which to return a URL.
 * @return string|null
 */
function infer_base_url( string $path ) {
	$path = wp_normalize_path( $path );

	$stylesheet_directory = wp_normalize_path( get_stylesheet_directory() );
	if ( strpos( $path, $stylesheet_directory ) === 0 ) {
		return get_theme_file_uri( substr( $path, strlen( $stylesheet_directory ) ) );
	}

	$template_directory = wp_normalize_path( get_template_directory() );
	if ( strpos( $path, $template_directory ) === 0 ) {
		return get_theme_file_uri( substr( $path, strlen( $template_directory ) ) );
	}

	// Any path not known to exist within a theme is treated as a plugin path.
	$plugin_path = get_plugin_basedir_path();
	if ( strpos( $path, $plugin_path ) === 0 ) {
		return plugin_dir_url( __FILE__ ) . substr( $path, strlen( $plugin_path ) + 1 );
	}

	return '';
}

/**
 * Return the path of the plugin basedir.
 *
 * @return string
 */
function get_plugin_basedir_path() {
	$plugin_dir_path = wp_normalize_path( plugin_dir_path( __FILE__ ) );

	$plugins_dir_path = wp_normalize_path( trailingslashit( WP_PLUGIN_DIR ) );

	return substr( $plugin_dir_path, 0, strpos( $plugin_dir_path, '/', strlen( $plugins_dir_path ) + 1 ) );
}

/**
 * Return web URIs or convert relative filesystem paths to absolute paths.
 *
 * @param string $asset_path A relative filesystem path or full resource URI.
 * @param string $base_url   A base URL to prepend to relative bundle URIs.
 * @return string
 */
function get_asset_uri( string $asset_path, string $base_url ) {
	if ( strpos( $asset_path, '://' ) !== false ) {
		return $asset_path;
	}

	return trailingslashit( $base_url ) . $asset_path;
}

function get_handle($asset_path){
	$scripthandle = substr(strrchr($asset_path, "/"), 1);
	$scripthandle = urlencode(substr($scripthandle,0,strpos($scripthandle,'.')));
	return $scripthandle;
}
/**
 * @param string $directory Root directory containing `src` and `build` directory.
 * @param array $opts {
 *     @type string $base_url Root URL containing `src` and `build` directory. Only needed for production.
 *     @type string $handle   Style/script handle. (Default is last part of directory name.)
 *     @type array  $scripts  Script dependencies.
 *     @type array  $styles   Style dependencies.
 * }
 */
function enqueue_assets( $directory, $opts = [], $isadmin = false ) {
	
	$defaults = [
		'base_url' => '',
		'handle'   => basename( $directory ),
		'scripts'  => [],
		'styles'   => [],
	];

	$opts = wp_parse_args( $opts, $defaults );

	$assets = get_assets_list( $directory );

	$base_url = $opts['base_url'];
	if ( empty( $base_url ) ) {
		$base_url = infer_base_url( $directory );
	}

	if ( empty( $assets ) ) {
		// TODO: This should be an error condition.
		return;
	}

	// There will be at most one JS and one CSS file in vanilla Create React App manifests.
	$has_css = false;
	$jscount = 0;
	$csscount = 0;
	foreach ( $assets as $asset_path ) {
		
		$is_js = preg_match( '/\.js$/', $asset_path );
		$is_css = preg_match( '/\.css$/', $asset_path );
		$scripthandle = get_handle($asset_path);
		
		
		if ( ! $is_js && ! $is_css ) {
			
			// Assets such as source maps and images are also listed; ignore these.
			continue;
		}
		
		if($scripthandle == 'editorstyles'){
			//echo '<h1>'.get_asset_uri( $asset_path, $base_url ).'</h1>';
			if($isadmin && $is_css){
				add_editor_style(get_asset_uri( $asset_path, $base_url ));			
			
			}
			// Editor only styles – ignore these on front end and dev.
			continue;
		}else if(!$isadmin){
			if ( $is_js ) {
				if( $jscount == 0 ) { 
					$scripthandle = $opts['handle'];
				}
				
				//echo '<h1>'.$scripthandle.'</h1>';
				// modernizr should be loaded into <head>
				$infooter = ($scripthandle !== 'modernizr');
				wp_enqueue_script(
					$scripthandle,
					get_asset_uri( $asset_path, $base_url ),
					$opts['scripts'],
					null,
					$infooter
				);
				$jscount=$jscount+1;
				
			} else if ( $is_css ) {
				if( $csscount == 0 ) { 
					$scripthandle = $opts['handle'];
				}
				
				$has_css = true;
				wp_enqueue_style(
					$scripthandle,
					get_asset_uri( $asset_path, $base_url ),
					$opts['styles']
				);
				$csscount++;
				
			}
		}
		
		
	}

	// Ensure CSS dependencies are always loaded, even when using CSS-in-JS in
	// development.
	if ( ! $has_css ) {
		wp_register_style(
			$opts['handle'],
			null,
			$opts['styles']
		);
		wp_enqueue_style( $opts['handle'] );
	}
}



