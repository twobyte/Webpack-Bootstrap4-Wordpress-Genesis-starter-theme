# Tasty Webpack Wordpress Genesis Child Theme using Bootstrap 4

[tastydigital.com/resources/tasty-webpack-starter](https://tastydigital.com/resources/tasty-webpack-starter)

## Wordpress theme integration

This repo should replace entire Wordpress theme folder within `wp-contents`.

Uses WEBPACK to build out front end assets; these assets are authored within the `./src` folder and written to a sub-directory called `./[theme-name]/dist` in the theme folder. I decided to structure the folders this way so all the build and package scripts are not cluttering up the main production theme folder.

Theme folder ([theme-name]) is currently called `./tasty-webpack-starter` by default, and contains the Wordpress PHP and other static assets. This is a child of `./genesis` theme, this parent theme folder should not be edited.

### Theme customisation

The `./tasty-webpack-starter` theme folder can be renamed to whatever your new theme is to be called. __If renaming you must also update the theme setting under config.theme in package.json__. 

Also within package.json update your config vars buildFolder, proxyURL and allowedHosts to match your local development env , I use a proxyURL from a hostname configured using MAMP or similar. Current local development host is http://tasty.local.

Clone the repo into wp-contents, make sure the repo is called `themes` then `cd themes` then `yarn` or `npm install` to get started.  

Use `yarn start` for live developing and `yarn production` to compress and write out all those assets ready for deployment.

Theme specific logo, favicon and google key can be specified near the top of the `[theme-name]/lib/theme.php` file. There’s lots of opinionated code so please feel free to update as you see fit.

## Installation

1. Replace the theme folder in your wp-content/ directory via FTP. (including the Genesis parent theme which needs to be in the wp-content/themes/ directory as well.) You can exclude the node_modules and src folders from the web-server as these are only necessary when authoring files in development.
2. Go to your WordPress dashboard and select Appearance and activate the Tasty Webpack theme.
3. Alternatively run `wp theme activate [theme-name]` from the command line if you have WP-CLI
4. Inside your WordPress dashboard, go to Genesis > Theme Settings and configure these to your liking.


### Props

Thanks to the following people for helping me get this far:

* [K Adam White and HumanMade] (https://github.com/humanmade/react-wp-scripts)
* [Matt Banks Genesis Starter Theme, where I started this WP integration journey using Browsersync/Grunt] (https://github.com/mattbanks/Genesis-Starter-Child-Theme)
* [Genesis framework by Studiopress] (https://www.copyblogger.com/genesis-framework-for-wordpress/)
* Many others too numerous to mention who helped out through my learning searches

