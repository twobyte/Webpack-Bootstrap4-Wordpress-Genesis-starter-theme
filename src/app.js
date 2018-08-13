// Stylesheets
import './sass/style.scss';

import jQuery from 'jquery';

import 'bootstrap';

import './scripts/cookie';

import * as FastClick from 'fastclick';
FastClick.attach(document.body);

// export for others scripts to use
window.$ = window.jQuery = $ = jQuery;


require("imports-loader?$=jquery!slicknav/dist/jquery.slicknav.js");
//import 'slicknav/dist/slicknav.css';

$('#genesis-nav-primary ul.menu-primary').slicknav({
		'allowParentLinks': true, 
		'closeOnClick': true
	});
