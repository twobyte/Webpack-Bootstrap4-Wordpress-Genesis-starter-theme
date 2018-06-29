const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
//const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
const ManifestPlugin = require('webpack-manifest-plugin');

//const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const themeFolder = process.env.npm_package_config_theme;
const proxyURL = process.env.npm_package_config_proxyURL;
const allowedHosts = process.env.npm_package_config_allowedHosts;
const buildFolder = process.env.npm_package_config_buildFolder
// Note: defined here because it will be used more than once.
const cssFilename = '[name].[contenthash:8].css';

const webPackFolder = buildFolder+'/'; // this is relative to within the themeFolder
const devPath = 'http://localhost:8080/wp-content/themes/'+themeFolder+'/'+webPackFolder;
const outputPath = (process.env.NODE_ENV !== 'development') ? webPackFolder : devPath;


function getFee(isMember) {
  return (isMember ? "$2.00" : "$10.00");
}
const extractSass = new ExtractTextPlugin({
	filename: cssFilename,
    allChunks: true,
    disable: process.env.NODE_ENV === "development"
});

 
module.exports = {
  context: path.resolve(__dirname, './src'),
  entry: {
  	app: ['./app.js'],
  	//main: ['../sass/main.scss'],
  	modernizr: './modernizr.js'
  	//     app: './app.js',
  },
  output: {
    path: path.resolve(__dirname, themeFolder, './'+buildFolder),
    filename: '[name].[chunkhash:8].js',
    publicPath: webPackFolder
  },
  devtool: "source-map", // any "source-map"-like devtool is possible
  devServer: {
	contentBase: path.join(__dirname, '../../'),
	publicPath: devPath, 
	proxy: {
        '/': {
            target: {
                host: proxyURL,
                protocol: "http:"
            },
            changeOrigin: true,
            secure: false
        }
    },
    allowedHosts: allowedHosts
  }, 
  module: {
    rules: [
    	{
	        test: /\.js$/,
	        exclude: [/node_modules/],
	        use: [{
	        	loader: 'babel-loader',
	        	options: { presets: ['env'] },
	        }],
	    },
		{
	        test: /\.(scss|css|sass)$/,
	        use: extractSass.extract({
	            use: [{
	                loader: "css-loader",
	                options: {
						importLoaders: 2,
	                    sourceMap: true
	                }
	            }, 
	            {
		            loader: 'postcss-loader',
		            options: {
		            	sourceMap: true
		            }
		        }, 
	            {
		            loader: 'resolve-url-loader',
		            options: {
		            	sourceMap: true
		            }
		        },
		        {
	                loader: "sass-loader",
	                options: {
	                    //includePaths: ["src/sass"],
	                    sourceMap: true
	                }
	            }],
	            // use style-loader in development
	            fallback: "style-loader"
	        })
        },
		{
			test: /\.(jpg|jpeg|gif|png)$/,
			//exclude: /node_modules/,
			loader:'url-loader?limit=1024&name=images/[name].[ext]'
		},
		{
			test: /\.(woff|woff2|eot|ttf|svg)$/,
			//exclude: /node_modules/,
			loader: 'url-loader?limit=1024&name=fonts/[name].[ext]'
		},
		{
			test: /\.modernizrrc.js$/,
			use: [ 'modernizr-loader' ]
		},
		{
			test: /\.modernizrrc(\.json)?$/,
			use: [ 'modernizr-loader', 'json-loader' ]
		}
      // Loaders for other file types can go here
    ],
  },
  plugins: [
    extractSass,
    new webpack.optimize.ModuleConcatenationPlugin(),
    new webpack.ProvidePlugin({
	    $: 'jquery',
	    jQuery: 'jquery' 
	}),
	new webpack.DefinePlugin({
		'process.env': {
			'NODE_ENV': process.env.NODE_ENV
		}
	}),
	// Generate a manifest file which contains a mapping of all asset filenames
    // to their corresponding output file so that tools can pick it up without
    // having to parse `index.html`.
    new ManifestPlugin({    
      fileName: '../asset-manifest.json',
      writeToFileEmit: true,
      publicPath: outputPath
    }),
	//new BundleAnalyzerPlugin()
    //browserSync
  ],
  resolve: {
    alias: {
      modernizr$: path.resolve(__dirname, ".modernizrrc"),
      jquery: "jquery/src/jquery"
    }
  },
  externals: {
	jquery: 'jQuery',
	PinUtils: 'PinUtils'
  }
  
};
