// Stylesheets
import './sass/style.scss';

import jQuery from 'jquery';
import Pjax from 'pjax';

import topbar from 'topbar';

import Flickity from 'flickity-imagesloaded';
import 'flickity/css/flickity.css';

import './scripts/cookie';
//import Instafeed from 'instafeed.js';

import * as FastClick from 'fastclick';
FastClick.attach(document.body);

// export for others scripts to use
window.$ = window.jQuery = $ = jQuery;

topbar.config({
    barColors    : {
      '0'        : '#008f9e',
      '1.0'      : '#008f9e'
    }
})

//alert('yo');
console.log('YO Mofo jo');
//import '@fancyapps/fancybox/dist/jquery.fancybox.js';
require("imports-loader?$=jquery!@fancyapps/fancybox/dist/jquery.fancybox.js");
import '@fancyapps/fancybox/dist/jquery.fancybox.css';

require("imports-loader?$=jquery!slicknav/dist/jquery.slicknav.js");
//import 'slicknav/dist/slicknav.css';

require("imports-loader?$=jquery!./vendor/jquery.krioImageLoader.js");




$().fancybox({
	selector : "a[rel^='lightbox']",
	loop     : true, 
	btnTpl 	 : {
		pinterest : '<a pinterest title="Pin it!" data-pin-do="buttonPin" data-pin-custom="true" class="fancybox-button fancybox-pinterest"></a>',
	},
	buttons	 : [
		'slideShow',
		'fullScreen',
		'thumbs',
		'pinterest',
		'close'
	],
	beforeShow : function( instance, current ) {
		$('.fancybox-pinterest').attr('data-media', 'http://'+location.host + current.src);
	}
    
});

$('#sidebar > nav > ul').slicknav({
		'prependTo': '#mobile-menu',
		'allowParentLinks': true, 
		'closeOnClick': true
	});

var flkty,
isFlickity = false,
pinit = '<img src="assets/img/icon-pinterest.png" alt="Save to Pinterest" width="20" height="20" class="social-icons pin-it">';

const supportsSVG = function() {
    return !! document.createElementNS && !! document.createElementNS('http://www.w3.org/2000/svg','svg').createSVGRect;  
},
initMap = function() {
	var workshop = new google.maps.LatLng(51.371813,-0.209236);
	var myOptions = {
		center: workshop,
		zoom: 15,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var marker = new google.maps.Marker({
		position: workshop,
		map: map,
		title:"Deen Builders",
		animation: google.maps.Animation.DROP
	});
	var contentString = '<span id="span_map_bubble"><strong>Deen Builders</strong></span>';
	
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	
	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
	// To add the marker to the map, call setMap();
	marker.setMap(map);
},
initGalleries = function(){
	//console.log('e '+document.getElementById('main').getElementsByClassName('cycle-slideshow2').length);
	
	
	var elem = document.querySelector('section.content:not(.js-Pjax-remove) > .cycle-slideshow2');
	
	if(elem){
		
		flkty = new Flickity( elem, {
			// options
			lazyLoad: 1, 
			imagesLoaded: true, 
			cellSelector: '.slide', 
			wrapAround: true, 
			autoPlay: 5000
		});
 		isFlickity = true;

	}
	
	if (!supportsSVG()) {
		$('img[src$="svg"]').attr('src', function() {
	        return $(this).attr('src').replace('.svg', '.png');
	    });
	}
	if($("#map_canvas").length == 1){
		//console.log("map canvas found");
		initMap();
	}
	if ($.fn.krioImageLoader) {
		//console.log('krio: '+$("section.content") );
	   $(".grid, .halfcol, .thumb").krioImageLoader();
	}
},
updateNav = function() {
	var
		/* Application Specific Variables */
		//$menu = $('#mobile-menu .slicknav_menu').filter(':first'),
		$menu = $('#sidebar nav, #mobile-menu .slicknav_menu'),
		activeClass = 'active',
		activeSelector = '.active',
		menuChildrenSelector = 'li',
		$menuChildren = $menu.find(menuChildrenSelector);
		
	$menuChildren.filter(activeSelector).removeClass(activeClass);
	
	
	var pathArray = location.pathname.split( '/' );
	var path = '';

	for (var i = 0; i < pathArray.length; i++) {
		if(pathArray[i]!=''){
			
			if(i < pathArray.length-1){
				path += pathArray[i]+'/';
				
			}else{
				// end bit
				var pos = pathArray[i].indexOf("?");
				if ( pos > -1 ) pathArray[i] = pathArray[i].slice(0,pos);
				path += pathArray[i];
			}
		}
	}
	if(path == ''){
		path = location.href;
	}
	
	//console.log('location.pathname = ' + location.pathname + '; location.href = ' + location.href + '; path = ' + path + '; pathArray.length = ' + pathArray.length + ';');
	$menuChildren.has('a[href="'+path+'"],a[href="/'+path+'"]').addClass(activeClass);
},
resizeEvent = function(){
	// ensure fit
	//$('#main').css({'height':($('#main > section.content:not(.js-Pjax-remove)').filter(':first').height()+'px')});
	//console.log('resizeEvent: width = '+ $(window).width() +'; window.innerWidth: '+window.innerWidth);
	
	// reposition adornements
	
	if($(window).width() < (990-15))
    {
        //Mobile
        //console.log('in sidebar: '+$('#wrapper > #sidebar > .adornments'));
        $('#wrapper > #sidebar > .adornments').prependTo($('#wrapper > footer'));
    }
    else
    {
        //Desktop
        //Leave original layout or move back..
        //console.log('in footer: '+$('#wrapper > footer > .adornments'));
        $('#wrapper > footer > .adornments').appendTo($('#wrapper > #sidebar'));
    }
},
loadEvent = function(){
	// ensure fit
	// console.log('loaded');
	if ($.fn.krioImageLoader) {
		//console.log('krio: '+$("section.content") );
	   $("#sidebar").krioImageLoader();
	}
	resizeEvent();
},
whenDOMReady = function() {
	updateNav();
	// add #loaderbox if not loaded
	if($( "#wrapper > #loaderbox" ).length === 0 ){
		$( "#wrapper" ).append( "<div id=\"loaderbox\"><img src=\"assets/img/Deen-spinner.gif\" alt=\"loading...\"></div>" );
	}
	// Initialise slider galleries.
	initGalleries();
	//resizeEvent();
	
	/* pinterest stuff */
	$('.cycle-slideshow2, .thumbs_grid').after(pinit);

}

new Pjax({
	//elements: 'a:not(.no-ajaxy,[href$=".jpg"],#lbCloseLink,#lbPrevLink,#lbNextLink)', // default is "a[href], form[action]"
	//elements: 'a:not(.no-ajaxy,[href$=.jpg],#lbCloseLink,#lbPrevLink,#lbNextLink)', 
	elements: "#sidebar a, [href$=html], .ruled a", 
	selectors: ["title", '#main'],
	switches: {
		'#main': require("pjax/lib/switches.js").sideBySide
	},
	switchesOptions: {
		'#main': {
			classNames: {
		        // class added on the element that will be removed
		        //remove: "Animated Animated--reverse Animate--fast Animate--noDelay",
		        remove: "Animated Animate--fadeOut",
		        // class added on the element that will be added
		        add: "Animated",
		        // class added on the element when it go backward
		        backward: "Animate--slideInRight",
		        // class added on the element when it go forward (used for new page too)
		        forward: "Animate--slideInLeft"
		    },
		    callbacks: {
		        // to make a nice transition with 2 pages as the same time
		        // we are playing with absolute positioning for element we are removing
		        // & we need live metrics to have something great
		        // see associated CSS below
		        removeElement: function(el) {
			        //debugger;
			        if(isFlickity){
						flkty.destroy();
						isFlickity = false;
					}
			        $(el).fadeOut(600, function() { 
				        

				        $(this).remove(); 
				        
	
				    });
			        //style.marginRight = "-" + (el.getBoundingClientRect().width/2) + "px"
			    }
			}
	    }
	},
	scrollTo: false
});


whenDOMReady();
document.addEventListener("pjax:success", whenDOMReady);

window.addEventListener('load', loadEvent);
window.addEventListener('resize', resizeEvent);
  
  
document.addEventListener('pjax:send', function() {
	var offSetTop = 36;
	topbar.show();
	
	$('body').addClass('loading');
	
	// hack to stop images jump changing when flickity is destroyed.
	$('.flickity-slider > .slide:not(.is-selected)').hide();
	if($(document).scrollTop() > offSetTop){
		$('html,body').animate({scrollTop:offSetTop},800);
	}

	
})
document.addEventListener('pjax:complete', function() {
	topbar.hide();
	$('body').removeClass('loading');
})

/*

var userFeed = new Instafeed({
    get: 'user',
    userId: '4391444537',
    accessToken: '4391444537.2e96bec.27bb62d2db1a46af9e33da51c3530fba',
    resolution: 'thumbnail',
    limit: 9,
    template: '<div class="insta_thumb"><a href="{{link}}" class="insta_link" target="_blank"><img src="{{image}}" alt="{{caption}}" data-pin-nopin="true" /></a></div>', 
    target: 'instapics'
});
userFeed.run();
*/


$('#main').on('click', '.pin-it', function(){
    if (typeof PinUtils == undefined) {
        jQuery.getScript("http://assets.pinterest.com/js/pinit_main.js", function() {
            PinUtils.build();
            PinUtils.pinAny();
        });
    } else {
        PinUtils.pinAny();
    }
});

$('body').on('click', '.fancybox-button.fancybox-pinterest', function(e) {
	if (typeof PinUtils == undefined) {
        jQuery.getScript("http://assets.pinterest.com/js/pinit_main.js", function() {
            PinUtils.build();
            PinUtils.pinOne({
		        media: e.target.getAttribute('data-media'),
		        description: e.target.getAttribute('data-description')
		    });
        });
    } else {
        PinUtils.pinOne({
	        media: e.target.getAttribute('data-media'),
	        description: e.target.getAttribute('data-description')
	    });
    }
    
    
	
	
    
});
