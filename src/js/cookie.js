import jQuery from 'jquery';

// Ask for cookies consent
jQuery(document).ready(function ($) {
        var consent = document.cookie.match('(?:^|;)\\s*_cookieconsent=([^;]*)');
        consent = (consent) ? decodeURIComponent(consent[1]) : null;
        //var msg = cookiedata.cookie_notice_text;
        var msg = 'We use cookies to give you the best possible experience on our website. By continuing to browse this site, you give consent for cookies to be used. For more details please read our <a href="/sovereign-privacy-policy/">Privacy Policy</a>'; 
        if (consent != "yes" && msg != '')
        {
	        
            document.cookie = '_cookieconsent=; path=/;';
            var code = '<div id="cookie-consent" style="z-index:5000;"><div class="holder"><p>'+msg+'</p> <a id="cookieconsent-continue" href="#" class="closer button" title="Close and agree" role="button" aria-label="Close and agree">&times;</a></div></div>';
            $(".site-container").after(code);
            $("#cookieconsent-continue").click(function (event) {
                    this.blur();
                    event.preventDefault();
                    $("#cookie-consent").slideUp(200);
                    var date = new Date();
                    date.setTime(date.getTime()+(10*365*24*60*60*1000));
                    document.cookie = '_cookieconsent=yes; path=/; expires=' + date.toGMTString();
            }).focus();
            $("#cookie-consent").slideDown(400);
            
           
        }
});

