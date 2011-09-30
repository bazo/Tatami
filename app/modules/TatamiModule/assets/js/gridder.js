/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){

/*
    $('.gridder a.ajax').live('click', function(event){
	   event.preventDefault();
	    $.get(this.href);
      }); 

  $('form.ajax input[type="submit"], form.ajax button[type="submit"]').live('click', function (event) {
	event.preventDefault();
	$(this).ajaxSubmit();
	return false;
    });
*/
    $('.gridder .paginator select[name="page"]').live('change', function(){
	$(this).parents('form').ajaxSubmit();
    });

    $(".gridder .activity-indicator").ajaxStart(function(){
       $(this).text('buu');
     });

   $('.gridder').livequery(function(){
       
    
      
       /*
       
      $('th.select_filters a.all').click(function(event){
        $('.row_checkbox input').attr('checked', true);
        $('tr.even, tr.odd').addClass('selected');
        event.preventDefault();
      });
      
      $('th.select_filters a.none').click(function(event){
        $('.row_checkbox input').attr('checked', false);
        $('tr.even, tr.odd').removeClass('selected');
        event.preventDefault();
      });

      $('.row_checkbox input').change(function(){
          if($(this).attr('checked'))
          {
            $(this).parent().parent().addClass('selected');
          }
          else
          {
            $(this).parent().parent().removeClass('selected');
          }
      });

      $('.cancel-filter').click(function(){
        var parentId = $(this).attr('data-parent');
        $('#'+parentId).val(null);
      });
      */
    /*
    $(this).children('tbody').ajaxStart(function(){
        var height = $(this).height();
        var width = $(this).width();
        var pos = $(this).offset();

        var loader_top =  (height / 2) - 34;
        var loader_left = (width / 2) - 34;
        $(this).append('<div class="overlay"><span class="loader"></span></div>');
        $('.overlay').css('height', height).css('width', width).css('top', pos.top).css('left', pos.left).show();
        $('.overlay .loader').css('top', loader_top).css('left', loader_left);
    }).ajaxError(function(){
        $('.overlay').hide();
    }).ajaxStop(function(){
        $('.overlay').hide();
    });
    */
   });
});

