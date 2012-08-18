$(document).ready(function() {

//checking checked checkboxes - browse members
$("#form-browse-members input:checkbox").each(function(){
  if($(this).is(':checked')){
    var currentId = $(this).attr("id");
    $("#fl-filtr-form-choose-role li img#"+currentId).parent().addClass("selected");
  }
});

//checking checked checkboxes - browse projects
$("#form-browse-projects input:checkbox").each(function(){
  if($(this).is(':checked')){
    var currentId = $(this).attr("id");
    $("#fl-filtr-form-choose-role li img#"+currentId).parent().addClass("selected");
  }
});

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