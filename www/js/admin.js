$(document).ready(function(){
   //time
   $('#time').jclock();

   //ajaxifying the links
    $("a.ajax").live("click", function (event) {
        event.preventDefault();
        $.get(this.href);
    });


    //button states

    $('.button').livequery(function(){
        $(this).addClass('ui-state-default')
        .hover(function() {
            $(this).addClass('ui-state-hover');
        }, function() {
            $(this).removeClass('ui-state-hover');
        })
        .mousedown(function(){
            $(this).addClass('ui-state-active');
        })
        .mouseup(function(){
            $(this).removeClass('ui-state-active');
        });

    });

   //$('.button').livequery(function(){$(this).button()}); nice, but makes the buttons very large

    //flash message effects
    $('.flash').livequery(function(){
        //document.documentElement.scrollTop

       var flash = $(this);
       var flashTop = flash.offset().top;
       var width = flash.width();
       var scrollTop = document.documentElement.scrollTop;
       if(scrollTop > flashTop)
       {
           flash.css({position: 'absolute',
           display: 'block',
            width: width,
            top: scrollTop,
        'z-index': 10});
       }
       flash.fadeIn('slow').delay(5000).fadeOut('slow');
       //flash.slideDown('slow').delay(5000).slideUp('slow');
    });

    //popup window effects
    $('#popup_window').livequery(function(){
        var popup = $(this);//$('#popup_window');
        var popup_width = popup.width();
        var popup_height = popup.height();
        var screen_height = $(window).height();
        var top = Math.floor((screen_height - popup_height) / 2);
        if(top < 0) top = 0;

        popup.css({
            'top':  Math.floor(screen_height / 2),
            'height': 0,
            'width' : 0,
            'display': 'none'
        })
        .animate({
            'height' : popup_height,
            'width' : popup_width,
            'display': 'table',
            'top': top
        },'fast', 'swing')
        .draggable({handle: '.drag_handle'});

        popup.find('div.popup_close a').click(function(event){
            popup.parent().fadeOut();
            popup.parent().parent().html('');
            event.preventDefault();
            return false;
        });
    });

    //confirmation dialog
    $('.confirm-dialog').livequery(function(){
        $(this).find('div').slideDown('fast', function(){
        });
    });

    $("form.ajax").submit(function (event) {
            $(this).ajaxSubmit();
            event.preventDefault();
    });
/*
    $('form.ajax input[type="submit"], form.ajax button[type="submit"]').live('click', function (event) {
            event.preventDefault();
            $(this).ajaxSubmit();
            return false;
    });
*/

    $('input.datepicker').livequery(function(){
        $(this).datepicker({duration: 'fast'});
    });

    $("#wrapper > header > .module-switcher > h2 > a.selector").click(function(){
        $(this).focus().parent().next().fadeIn();
        return false;
    }).blur(function(){
        $(this).parent().next().fadeOut();
        return false;
    });

    $("#jMenu").jMenu({
      ulWidth : 'auto',
      effects : {
        effectSpeedOpen : 300,
        effectSpeedClose : 300,
        effectTypeOpen : 'slide',
        effectTypeClose : 'slide',
        effectOpen : 'linear',
        effectClose : 'linear'
      },
      TimeBeforeOpening : 100,
      TimeBeforeClosing : 400,
      animatedText : false,
      paddingLeft: 1
    });
    
});