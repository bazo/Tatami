$(document).ready(function(){
    
    var widgetContainer = $('#widgets');
    var height = $(document).height() - widgetContainer.offset().top;
    widgetContainer.css({height: height+'px'});
    
    $('.icon.settings').click(function(){
	var widgets = $('#widgets');
	if(widgets.hasClass('settings-open'))
	{
	    widgets.removeClass('settings-open');
	}
	else
	{
	    widgets.addClass('settings-open');
	    $('.dashboard-widget').draggable({
	    containment: 'body',
	    revert: 'invalid'
	});
	    $('#available-widgets').droppable(
	    {
		accept: '.dashboard-widget',
		drop: function(event, ui){
		    var widget = $(ui.draggable)
		    var widgetName = widget.data('name');

		    var url = $(this).data('ondrop-callback');
		    $.post(url, {widgetName: widgetName}, function(data, textStatus, jqXHR){
			jQuery.nette.success(data);
			widget.remove();
		    });
		}
		
	    }
	    );
	}
	$('#available-widgets').toggle();
    })
    
    $('.dashboard-widget').livequery(function(){
	$(this).draggable({
	    containment: '#widgets',
	    stop: function(event, ui){
		var position = ui.offset.top+':'+ ui.offset.left;
		$.cookie(widgetName, position);
	    }
	});
	var widgetName = $(this).data('name');
	
	var position = $.cookie(widgetName);
	if(position != null)
	{
	    var coords = position.split(':');
	    var top = parseInt(coords[0]);
	    var left = parseInt(coords[1]);
	    $(this).css({position: 'absolute', top: top+'px', left: left+'px'});
	}
	
    });
    
    $('.available-dashboard-widget').livequery(function(){
	$(this).draggable({
	    revert: "invalid", 
	    containment: "body"
	});
    });
    
    $('#widgets').droppable({
			    accept: '.available-dashboard-widget',
			    drop: function( event, ui ) {
				var widget = $(ui.draggable);
				var widgetName = widget.data('name');
				
				var position = widget.offset().top+':'+ widget.offset().left;
				$.cookie(widgetName, position);
				
				var url = $(this).data('ondrop-callback');
				$.post(url, {widgetName: widgetName}, function(data, textStatus, jqXHR){
				    jQuery.nette.success(data);
				    widget.remove();
				});
			    }
			    });
});