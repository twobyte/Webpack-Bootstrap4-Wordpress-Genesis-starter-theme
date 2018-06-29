# Tasty Webpack Wordpress Genesis Child Theme

[tastydigital.com/themes/tasty-webpack-starter](https://tastydigital.com/themes/tasty-webpack-starter)

## BUILD

This repo should replace entire theme folder within wp-contents.

Uses WEBPACK to build out front end assets, these assets are authored within ./src folder. 

Webpack writes out these front end assets to ./dist folder in the themes sub-floder folder.  

Theme folder is currently called ./tasty-webpack-starter by default and contains the Wordpress PHP and other static assets.

This theme folder can be renamed to whatever your new theme is to be called. If renaming you must also update the theme setting under config.theme in package.json. 

Also within package.json update your config vars buildFolder, proxyURL and allowedHosts to match your local development env , I use a proxyURL from a hostname configured using MAMP or similar. 

Get started with `yarn` or `npm install` to get started.  `yarn start` for live developing and `yarn production` to compress and write out all those assets ready for deployment.

Theme specific logo, favicon and google key can be specified near the top of [theme-name]/lib/theme.php file.

## INSTALL

1. Replace the theme folder in your wp-content/ directory via FTP. (including the Genesis parent theme which needs to be in the wp-content/themes/ directory as well.) You can exclude the node_modules and src folders from the web-server as these are only necessary when authoring files in development.
2. Go to your WordPress dashboard and select Appearance.
3. Activate the Tasty Webpack theme. or run `wp theme activate [theme-name]`
4. Inside your WordPress dashboard, go to Genesis > Theme Settings and configure them to your liking.


