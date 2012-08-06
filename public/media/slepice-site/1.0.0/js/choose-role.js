$(document).ready(function() {

$("#fl-filtr-form-choose-role li img").click( function(){
    var currentId = $(this).attr("id"); 
    console.log(currentId);
    var input = $("input:checkbox#"+currentId); 
    console.log(input);

    if( input.is(':checked') ) {
       input.attr('checked',false);
       $(this).parent().removeClass("selected");
    } else {
       input.attr('checked',true);
       $(this).parent().addClass("selected");
    } 

});

});