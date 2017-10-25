const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');
//const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const themeFolder = 'tasty-webpack-theme';
const proxyURL = 'tasty.local';
const allowedHosts = [
	    '.tsty.co',
	    '.tasty.local'
	];


const extractSass = new ExtractTextPlugin({
    //filename: "[name].[contenthash].css",
    // changing app.css to WP friendly style.css,
    filename:  (getPath) => {
	    if (process.env.NODE_ENV !== 'development') {
		    // pop it in theme root folder as required by Wordpress
	    	return getPath('../[name].css').replace('app', 'style');
	    }else{
		    // webpack dev server doesn't seem able to stream outside output path
		    return getPath('[name].css').replace('app', 'style');
	    }
    },
    allChunks: true,
    //disable: process.env.NODE_ENV === "development"
});

/*
const browserSync = new BrowserSyncPlugin(
{
    //proxy: 'http://'+proxyURL,
    proxy: 'http://localhost:8080',
    host: 'localhost',
        port: 3000,
    files: [
        {
            match: [
                '**//*.php' <-remove one slash
            ],
            fn: function(event, file) {
                if (event === "change") {
                    const bs = require('browser-sync').get('bs-webpack-plugin');
                    bs.reload();
                }
            }
        }
    ]
},
{
    reload: false
});
    
*/    
module.exports = {
  context: path.resolve(__dirname, './src'),
  entry: {
  	app: ['./app.js'],
  	//main: ['../sass/main.scss'],
  	modernizr: './modernizr.js'
  	//     app: './app.js',
  },
  output: {
    path: path.resolve(__dirname, themeFolder, './dist'),
    filename: '[name].js',
  },
  devtool: "source-map", // any "source-map"-like devtool is possible
  devServer: {
	 contentBase: path.join(__dirname, '../../'),
	 publicPath: 'http://localhost:8080/wp-content/themes/tasty-webpack-theme/dist/', 
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
