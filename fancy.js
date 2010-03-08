jQuery(function(){
  // group gallery items
  jQuery('div.gallery a').each(function(){
    jQuery(this).attr('rel', jQuery(this).parent().attr('id'));
  });
    
  // Add Fancy Classes to single items:
  jQuery('a').each(function(){
    // filter items
    if ( this.href.toLowerCase().substr(-4).indexOf('.jpg') < 0 &&
         this.href.toLowerCase().substr(-5).indexOf('.jpeg') < 0 &&
         this.href.toLowerCase().substr(-4).indexOf('.png') < 0 &&
         this.href.toLowerCase().substr(-4).indexOf('.gif') < 0 )
    return;
    
    // shorter access path
    var $lnk = jQuery(this); 

    // Add the fancybox class
    $lnk.addClass('fancybox');
    
    // Copy the title tag from link to img
    var $img = $lnk.find('img');
    if ( $img.attr('title') == '' ) $img.attr('title', $img.attr('alt'));
    if ( $lnk.attr('title') == '' ) $lnk.attr('title', $img.attr('title'));
  });
  
  jQuery('a.fancybox')
  .unbind('click')
  .fancybox({ 'speedIn'    : 500,
              'speedOut'   : 500,
              'transitionIn'   : 'elastic',
              'transitionOut'  : 'elastic',
              'titlePosition'	: 'over'
               
              });

});