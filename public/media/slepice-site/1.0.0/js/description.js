$(document).ready(function() {

  $(".fl-choosing-roles li").hover(function(){
    var role = $(this).attr('id');
    $(".fl-choosing-roles-description h4").text($("#hide_"+role+" h4").text());
    $(".fl-choosing-roles-description p").text($("#hide_"+role+" div").text());
  });

});