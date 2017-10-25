# Tasty Webpack Wordpress Genesis Child Theme

[tastydigital.com/themes/tasty-webpack](https://tastydigital.com/themes/tasty-webpack)

## BUILD

This repo should replace entire theme folder within wp-contents.

Uses WEBPACK to build out front end assets, these assets are authored within ./src folder. Webpack writes out these front end assets to ./dist folder in the actual theme folder.  Theme folder is called ./tasty-webpack-theme by default and contains the Wordpress PHP and other static assets and can be renamed to whatever the new theme is to be called. If renaming you must also update the themeFolder constant in webpack.config.js to match this name. 

Also within webpack.config.js update your dev env constants proxyURL and allowedHosts to your local development website hostname configured using MAMP or similar. Also check publicPath reflects your theme folder name.

Once theme folder named as required and constants updated get started with `yarn install` then `yarn run watch` to develop and `yarn run production` to compress and write out all those assets ready for deployment.

Theme specific logo, favicon and google key can be specified near the top of [theme-name]/lib/theme.php file.

## INSTALL

1. Replace the theme folder in your wp-content/ directory via FTP. (including the Genesis parent theme which needs to be in the wp-content/themes/ directory as well.) You can exclude the node_modules and src folders from the web-server as these are only necessary when authoring files in development.
2. Go to your WordPress dashboard and select Appearance.
3. Activate the Tasty Webpack theme.
4. Inside your WordPress dashboard, go to Genesis > Theme Settings and configure them to your liking.


