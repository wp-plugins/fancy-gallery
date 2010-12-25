jQuery(function($){
  
  // Activate the color picker
  jQuery ('input.color')
  .each(function(){
    var $this = jQuery(this);
    var $picker = $this.parent().find('.colorpicker');
    $picker
    .farbtastic($this)
    .hide();
  })
  .after('<span class="show_colorpicker"></span>');
  
  jQuery('input.color, span.show_colorpicker')
  .click(function(){
    jQuery(this).parent().find('.colorpicker').slideToggle();
  });
  
});
