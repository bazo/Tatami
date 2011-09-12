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
    /*
    var myLinks = document.getElementsByTagName('a');
for(var i = 0; i < myLinks.length; i++){
   myLinks[i].addEventListener('touchstart', function(){this.className = "hover";}, false);
   myLinks[i].addEventListener('touchend', function(){this.className = "";}, false);
}
*/
});