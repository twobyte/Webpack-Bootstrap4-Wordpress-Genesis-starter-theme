// tried to keep this as generic as possible, change configuration within package.json. (process.env.npm_package_config_*)
// if theme name changes also update themeFolder/style.css and themeFolder/functions.php

const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
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
const devMode = process.env.NODE_ENV !== 'production';
const outputPath = devMode ? devPath : webPackFolder;
const assetPath = devMode ? devPath : '';


 
module.exports = {
  context: path.resolve(__dirname, './src'),
  entry: {
  	app: ['./app.js'],
  	editorstyles: ['./sass/editor-style.scss'],
  	modernizr: './modernizr.js'
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
	        use: [
		        	devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
					{
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
		            },
		    ],
        },
		{
			test: /\.(jpg|jpeg|gif|png)$/,
			use: [{
                loader: 'file-loader',
                options: {
                    name: '[name].[ext]',
                    limit: 1024, 
                    outputPath: 'images/',
					publicPath:assetPath+'images/'
                }
            }]
		},
		{
            test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
            use: [{
                loader: 'file-loader',
                options: {
                    name: '[name].[ext]',
                    outputPath: 'fonts/',
					publicPath:assetPath+'fonts/'
                }
            }]
        },
		{
			test: /\.modernizrrc.js$/,
			exclude: /node_modules/,
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
    new MiniCssExtractPlugin({
		filename: cssFilename,
	    allChunks: true,
	    disable: devMode
    }),
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
  },
  optimization: {
    namedModules: true,
    namedChunks: true
  }  
  
};
