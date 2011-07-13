jQuery(function($){
  
  // Hide the senseless gallery setting fields
  $('table#basic tr:eq(0), table#basic tr:eq(3)').hide();
  
  // Add Fancy Gallery Pro Banner
  $('table#basic').before( $('div#fancy-gallery-banner') );
  
});
