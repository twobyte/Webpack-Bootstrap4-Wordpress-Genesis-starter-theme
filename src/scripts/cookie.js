import jQuery from 'jquery';

// Ask for cookies consent
jQuery(document).ready(function ($) {
        var consent = document.cookie.match('(?:^|;)\\s*_cookieconsent=([^;]*)');
        consent = (consent) ? decodeURIComponent(consent[1]) : null;
        
        if (consent != "yes")
        {
                document.cookie = '_cookieconsent=; path=/;';
                var code = '<div id="id_cookieconsent"><p>We use cookies to ensure you get the best web experience. If you don\'t change your browser settings, we\'ll assume you\'re happy to receive these cookies.</p> <a id="id_cookieconsent_continue" href="#" class="closer button" title="Close cookie notice">X</a></div>';
                $("#wrapper").prepend(code);
                $("#id_cookieconsent_continue").click(function (event) {
                        this.blur();
                        event.preventDefault();
                        $("#id_cookieconsent").slideUp("slow");
                        var date = new Date();
                        date.setTime(date.getTime()+(10*365*24*60*60*1000));
                        document.cookie = '_cookieconsent=yes; path=/; expires=' + date.toGMTString();
                });
                $("#id_cookieconsent").slideDown("slow");
        }
});

