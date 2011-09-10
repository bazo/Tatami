$(document).ready(function(){
    
    //ajaxifying the links
    $("a.ajax").live("click", function (event) {
        event.preventDefault();
        $.get(this.href);
    });
    
    $('form.ajax input[type="submit"], form.ajax button[type="submit"]').live('click', function (event) {
	event.preventDefault();
	$(this).ajaxSubmit();
	return false;
    });
});