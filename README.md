# Tasty Webpack Wordpress Genesis Child Theme

[tastydigital.com/themes/tasty-webpack](https://tastydigital.com/themes/tasty-webpack)

## BUILD

This repo should replace entire theme folder within wp-contents.

Uses WEBPACK to build out front end assets, these are assets are authored within src folder. Webpack writes out these front end assets to dist folder in actual theme folder.  Theme folder contains PHP and static assets and should be renamed to whatever the theme is to be called and also updating the themeFolder constant in webpack.config.js to match this name. 

Also update your dev env vars proxyURL and allowedHosts in webpack.config.js to your local development website hostname using MAMP or similar.

Once theme folder named and constants updated appropriately get started with yarn install then yarn run watch to develop and yarn run production to compress and write out all those assets ready for deployment.

Logo, favicon and google key can be specified near the top of [theme]/lib/theme.php file.

## INSTALL

1. Replace the theme folder in your wp-content/ directory via FTP. (including the Genesis parent theme which needs to be in the wp-content/themes/ directory as well.) You can exclude the node_modules and src folders from the web-server as these are only necessary when authoring files in development.
2. Go to your WordPress dashboard and select Appearance.
3. Activate the Tasty Webpack theme.
4. Inside your WordPress dashboard, go to Genesis > Theme Settings and configure them to your liking.


