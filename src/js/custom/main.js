//import Bootstrap from 'bootstrap-sass/assets/javascripts/bootstrap';

(function($, exports) {
	if( $("#children").length==1 ){
		new jQueryCollapse($("#children"), {
	        open: function() {
	            this.slideDown(150);
	        },
	        close: function() {
	            this.slideUp(150);
	        },
			accordion: true,
			persist: true
	    });
	}
	
	$('.clicker > a').on("click",function(e){
    	e.preventDefault();
	    $(this).parent().find(".sub-menu").toggle();
	    $(this).parent().siblings().find(".sub-menu").hide();
	    
	    if($(".sub-menu:visible").length === 0 ){
	      $("#menu-overlay").hide();
	    }else {
	      $("#menu-overlay").show();
	      $(this).addClass('clicked');
	    }
	    
	});
	$( "body" ).append( "<div id=\"menu-overlay\"></div>" );
	$("#menu-overlay").on("click",function(){
	   $(".sub-menu").hide();
	   $('.clicker > a').removeClass('clicked');
	   $(this).hide();
   	});
   
})(window.jQuery, window);
