<?php

// Send Header Mime type
Header ('Content-Type: text/javascript');


// Load WordPress
$wp_load = 'wp-load.php';
While (!Is_File ('wp-load.php')){
  If (Is_Dir('../')) ChDir('../');
  Else Die('Could not find WordPress.');
}
Include_Once 'wp-load.php';


// Is the class ready?
If (!Class_exists('wp_plugin_fancy_gallery')) Die ('Could not find the Fancy Gallery Plugin.');


/*
 * I use an anonymous function because we are in the global NameSpace.
 * You can access the "Load_Setting" function of the the plugin class via:
 * 
 * $setting = $load_setting($key, $default)
 *    
*/
$get_option = Create_Function(
  '$key, $default = False',
  'return call_user_func(Array(\'wp_plugin_fancy_gallery\', \'get_option\'), $key, $default);'
);


// Set image extensions
$arr_type = Array( 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'wbmp', 'ico' );


?>jQuery(function(){

  // group gallery items
  jQuery('div.fancy.gallery a')
  .each(function(){
    var $this = jQuery(this); 
    $this.attr('rel', $this.parent().attr('id'));
  });

  <?php If($get_option('img_block_fix')) : ?>
  jQuery('div.fancy.gallery a img').addClass('alignleft');
  jQuery('div.fancy.gallery').append('<div style="clear:both"></div>')
  <?php EndIf; ?>

    
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
  
    padding        :  <?php Echo IntVal($get_option('border_width', 10)) ?>,
    cyclic         :  <?php Echo $get_option('cyclic') ? 'true' : 'false' ?>,
    scrolling      : '<?php Echo $get_option('scrolling', 'auto') ?>',
    centerOnScroll :  <?php Echo $get_option('center_on_scroll') ? 'true' : 'false' ?>,
    overlayOpacity :  <?php Echo Round($get_option('overlay_opacity', 30)) / 100 ?>,
    overlayColor   : '<?php Echo $get_option('overlay_color', '#666') ?>',
    titleShow      :  <?php Echo $get_option('hide_image_title') ? 'false' : 'true' ?>,
    titlePosition  : '<?php Echo $get_option('title_position', 'outside') ?>',
    transitionIn   : '<?php Echo $get_option('transition_in', 'fade') ?>',
    transitionOut  : '<?php Echo $get_option('transition_out', 'fade') ?>',    
    speedIn        :  <?php Echo IntVal($get_option('speed_in', 300)) ?>,
    speedOut       :  <?php Echo IntVal($get_option('speed_out', 300)) ?>,
    changeSpeed    :  <?php Echo IntVal($get_option('change_speed', 300)) ?>,
    showCloseButton:  <?php Echo $get_option('hide_close_button') ? 'false' : 'true' ?>
        

  });

});
