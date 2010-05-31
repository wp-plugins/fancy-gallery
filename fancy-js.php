<?php

// Load settings
If (!IsSet($_REQUEST['class'])) Die ('No WordPress plugin class name broadcasted.');

// Send Header Mime type
Header ('Content-Type: text/javascript');

// Load WordPress
$wp_load = 'wp-load.php';
While (!Is_File ('wp-load.php')){
  If (Is_Dir('../')) ChDir('../');
  Else Die('Could not find WordPress.');
}
Include_Once 'wp-load.php';


/*
 * I use an anonymous function because we are in the global NameSpace.
 * You can access the "Load_Setting" function of the the plugin class via:
 * 
 * $setting = $load_setting($key, $default)
 *    
*/
$load_setting = Create_Function(
  '$key, $default = False',
  'return call_user_func(Array($_REQUEST[\'class\'], \'load_setting\'), $key, $default);'
);


// Set image extensions
$arr_type = Array( 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'wbmp', 'ico' );


?>jQuery(function(){

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

    cyclic         :  <?php Echo $load_setting('cyclic') ? 'true' : 'false' ?>,
    scrolling      : '<?php Echo $load_setting('scrolling', 'auto') ?>',
    centerOnScroll :  <?php Echo $load_setting('center_on_scroll') ? 'true' : 'false' ?>,
    overlayOpacity :  <?php Echo Round($load_setting('overlay_opacity', 30)) / 100 ?>,
    overlayColor   : '<?php Echo $load_setting('overlay_color', '#666') ?>',
    titleShow      :  <?php Echo $load_setting('hide_image_title') ? 'false' : 'true' ?>,
    titlePosition  : '<?php Echo $load_setting('title_position', 'outside') ?>',
    transitionIn   : '<?php Echo $load_setting('transition_in', 'fade') ?>',
    transitionOut  : '<?php Echo $load_setting('transition_out', 'fade') ?>',    
    speedIn        :  <?php Echo Round($load_setting('speed_in', 300)) ?>,
    speedOut       :  <?php Echo Round($load_setting('speed_out', 300)) ?>,
    changeSpeed    :  <?php Echo Round($load_setting('change_speed', 300)) ?>
    

  });

});
