$(document).ready(function(){
    //ajaxifying the links
    $("a.ajax").live("click", function (event) {
        event.preventDefault();
        $.get(this.href);
    });
});