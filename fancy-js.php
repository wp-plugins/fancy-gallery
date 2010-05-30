<?php

// Send Header Mime type
Header ('Content-Type: text/javascript');


/*
*/
Function FindWordPress (){
  $wp_load = 'wp-load.php';
  While (!Is_File ($wp_load)){
    If ( Is_Dir('../' . DirName($wp_load)) )
      $wp_load = '../' . $wp_load;
    Else
      return False;
  }
  return $wp_load;
}


/*
*/
Function Load_Setting ($key, $default = False){
  return call_user_func(Array($_REQUEST['class'], 'load_setting'), $key, $default);  
}


// Load WordPress
If (!$wordpress = FindWordPress())
  Die('Could not load WordPress.');
Else
  Include_Once $wordpress;


// Load settings
If (!IsSet($_REQUEST['class'])) Die ('No WordPress plugin class name broadcasted.');

// Set image extensions
$arr_type = Array( 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'wbmp', 'ico' );


?>

jQuery(function(){

  // group gallery items
  jQuery('div.gallery a').each(function(){
    jQuery(this).attr('rel', jQuery(this).parent().attr('id'));
  });
    
  // Add Fancy Classes to single items:
  jQuery('a').each(function(){
    // filter items
    if ( <?php ForEach ($arr_type AS $type) : ?>
         this.href.substr(-<?php Echo StrLen($type)+1 ?>).toLowerCase().indexOf('.<?php Echo $type ?>') < 0 &&
         <?php EndForEach; ?>
         true )
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
  .fancybox({

    cyclic         :  <?php Echo Load_Setting('cyclic') ? 'true' : 'false' ?>,
    scrolling      : '<?php Echo Load_Setting('scrolling', 'auto') ?>',
    centerOnScroll :  <?php Echo Load_Setting('center_on_scroll') ? 'true' : 'false' ?>,
    overlayOpacity :  <?php Echo Round(Load_Setting('overlay_opacity', 30)) / 100 ?>,
    overlayColor   : '<?php Echo Load_Setting('overlay_color', '#666') ?>',
    titleShow      :  <?php Echo Load_Setting('hide_image_title') ? 'false' : 'true' ?>,
    titlePosition  : '<?php Echo Load_Setting('title_position', 'outside') ?>',
    transitionIn   : '<?php Echo Load_Setting('transition_in', 'fade') ?>',
    transitionOut  : '<?php Echo Load_Setting('transition_out', 'fade') ?>',    
    speedIn        :  <?php Echo Round(Load_Setting('speed_in', 300)) ?>,
    speedOut       :  <?php Echo Round(Load_Setting('speed_out', 300)) ?>,
    changeSpeed    :  <?php Echo Round(Load_Setting('change_speed', 300)) ?>
    

  });

});
