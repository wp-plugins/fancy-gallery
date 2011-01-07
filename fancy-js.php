<?php

// Send Header Mime type
Header ('Content-Type: text/javascript');


// Load WordPress
While (!Is_File ('wp-load.php')){
  If (Is_Dir('../')) ChDir('../');
  Else Die('Could not find WordPress.');
}
Include_Once 'wp-load.php';


// Is the class ready?
If (!Class_exists('wp_plugin_fancy_gallery')) Die ('Could not find the Fancy Gallery Plugin.');


/*
 * I use anonymous functions because we are in the global NameSpace.
 *  
 * You can access the "get_option" function of the the plugin class via:
 * $option = $get_option($key, $default)
 *    
*/
$get_option = Create_Function(
  '$key, $default = False',
  'return call_user_func(Array(\'wp_plugin_fancy_gallery\', \'get_option\'), $key, $default);'
);

/*
 * I use anonymous functions because we are in the global NameSpace.
 *  
 * You can access the "get_image_title" function of the the plugin class via:
 * $title = $get_image_title($key, $attachment)
 *    
*/
$get_image_title = Create_Function(
  '$attachment',
  'return call_user_func(Array(\'wp_plugin_fancy_gallery\', \'get_image_title\'), $attachment);'
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
  
  <?php // Copy the image description via js in the title attributes
  If($get_option('use_as_image_title') == 'description'){
    ForEach(get_posts(Array(
      'post_type'      => 'attachment',
      'post_mime_type' => 'image',
      'numberposts'    => -1,
      'order'          => 'ASC',
      'orderby'        => 'ID'
    )) AS $i => $image) :
    ?>jQuery('img.wp-image-<?php Echo $image->ID ?>').attr('title', '<?php Echo AddSlashes($get_image_title($image)); ?>');
  <?php EndForEach; } ?>
  
  <?php If($get_option('img_block_fix')) : ?>
  jQuery('div.fancy.gallery a img')
    .addClass('alignleft')
    .css({
      display: 'inline',
      clear: 'none',
      float: 'none'
    });
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
    var $img = $lnk.find('img'); 

    // Add the fancybox class
    $lnk.addClass('fancybox');
    
    <?php If ($get_option('associate_single_images')) : // Give em a rel attribute ?>
    if ($lnk.attr('rel') == '')
      $lnk.attr('rel', 'single-image');
    <?php EndIf; ?>
    
    <?php If ($get_option('use_as_image_title') == 'alt_text') : // Copy the alternate texts ?>    
    $img.attr('title', $img.attr('alt'));
    <?php ElseIf ($get_option('use_as_image_title') == 'caption') : // Copy the captions ?>
    if (caption = $lnk.parent('.wp-caption').find('.wp-caption-text').html())
      $img.attr('title', caption);
    <?php EndIf; ?>
    
    // Copy the title tag from link to img
    $lnk.attr('title', $img.attr('title'));
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
    titleShow      :  <?php Echo ($get_option('use_as_image_title')=='none') ? 'false' : 'true' ?>,
    titlePosition  : '<?php Echo $get_option('title_position', 'float') ?>',
    transitionIn   : '<?php Echo $get_option('transition_in', 'fade') ?>',
    transitionOut  : '<?php Echo $get_option('transition_out', 'fade') ?>',    
    speedIn        :  <?php Echo IntVal($get_option('speed_in', 300)) ?>,
    speedOut       :  <?php Echo IntVal($get_option('speed_out', 300)) ?>,
    changeSpeed    :  <?php Echo IntVal($get_option('change_speed', 300)) ?>,
    showCloseButton:  <?php Echo $get_option('hide_close_button') ? 'false' : 'true' ?>        

  });

});
