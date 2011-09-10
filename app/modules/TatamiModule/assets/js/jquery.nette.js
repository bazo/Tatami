/**
 * AJAX Nette Framwork plugin for jQuery
 *
 * @copyright   Copyright (c) 2009 Jan Marek
 * @license     MIT
 * @link        http://nettephp.com/cs/extras/jquery-ajax
 * @version     0.2
 */

jQuery.extend({
	nette: {
		updateSnippet: function (id, html) {
                        var snippet = $("#" + id);
                        snippet.html(html);
                        if(id == 'snippet--flash')
                        {
                            snippet.slideDown('slow').delay(5000).slideUp('slow');
                        }
                        if(id == 'snippet--popup')
                        {
                            var popup = $('#popup-window');
                            var popup_width = popup.width();
			    var popup_height = popup.height();
			    var screen_height = $(window).height();
			    var top = Math.floor((parseInt(screen_height) - parseInt(popup_height)) / 2)+"px";
			    //popup_width = parseInt(popup_width)+5+"px";//plus 5, because components in windows->chrome sometimes moving down

			    if(top < 0) top = 20;
			    popup.css({
				'top':  Math.floor(screen_height / 2),
				'height': 0,
				'width' : 0,
				'display': 'block'
			    })
			    .animate({
				'height' : popup_height,
				'width' : popup_width,
				'display': 'block',
				'top': top
			    },'fast', 'swing', function(){
				$(this).css('min-height', popup_height);
			    });
                        }
		},

		success: function (payload) {
			// redirect
			if(payload){
			    if (payload.redirect) {
				window.location.href = payload.redirect;
				return;
			    }

			    // snippets
			    if (payload.snippets) {
				for (var i in payload.snippets) {
				    jQuery.nette.updateSnippet(i, payload.snippets[i]);
				}
			    }
			}
		}
	}
});

jQuery.ajaxSetup({
	success: jQuery.nette.success,
	dataType: "json"
});