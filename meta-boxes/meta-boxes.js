jQuery(document).ready(function($){

  $('div#gallery-meta-box-excerpt h3 input')
  .click(function(){
    $(this).parent().next('.togglebox').slideDown().siblings('.togglebox').slideUp();
  })
  .filter(':checked').click();
  
});