<?php

// Send Header Mime type
Header ('Content-Type: text/javascript');

// Check Referer
If (IsSet($_SERVER['HTTP_REFERER'])){
  $referer = Parse_URL($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
  If (!Empty($referer) && !Empty($_SERVER['SERVER_NAME'])){
    If (StrIPos($referer, $_SERVER['SERVER_NAME']) === False) : ?>
    alert("Wrong Referer for <?php Echo BaseName(__FILE__) ?>!\n\nHost: <?php Echo $_SERVER['SERVER_NAME'] ?>\nReferer: <?php Echo $referer ?>");
    window.location.href = "http://<?php Echo $_SERVER['SERVER_NAME'] ?>/";
    <?php Exit; Endif;
  }
}

// Load WordPress
While (!Is_File ('wp-load.php')){
  If (Is_Dir('../')) ChDir('../');
  Else Die('Could not find WordPress.');
}
Include_Once 'wp-load.php';

// Is the class ready?
If (!Class_exists('wp_plugin_fancy_gallery')) Die ('Could not find the Fancy Gallery Plugin.');
Global $wp_plugin_fancy_gallery;

// Set image extensions
$arr_type = Array( 'jpg', 'jpeg', 'png', 'gif', 'bmp', 'wbmp', 'ico' );


?>jQuery(function(){

  // group gallery items
  jQuery('div.fancy-gallery a')
  .each(function(){
    var $this = jQuery(this); 
    $this.attr('rel', $this.parent().attr('id'));
  });
  
  <?php // Copy the image description via js in the title attributes
  If($wp_plugin_fancy_gallery->get_option('use_as_image_title') == 'description'){
    ForEach(get_posts(Array(
      'post_type'      => 'attachment',
      'post_mime_type' => 'image',
      'numberposts'    => -1,
      'order'          => 'ASC',
      'orderby'        => 'ID'
    )) AS $i => $image) :
    ?>jQuery('img.wp-image-<?php Echo $image->ID ?>').attr('title', '<?php Echo AddSlashes($wp_plugin_fancy_gallery->get_image_title($image)); ?>');
  <?php EndForEach; } ?>
  
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
    
    <?php If ($wp_plugin_fancy_gallery->get_option('associate_single_images')) : // Give em a rel attribute ?>
    if ($lnk.attr('rel') == '' || $lnk.attr('rel') == undefined)
      $lnk.attr('rel', 'single-image');
    <?php EndIf; ?>
    
    <?php If ($wp_plugin_fancy_gallery->get_option('use_as_image_title') == 'alt_text') : // Copy the alternate texts ?>    
    $img.attr('title', $img.attr('alt'));
    <?php ElseIf ($wp_plugin_fancy_gallery->get_option('use_as_image_title') == 'caption') : // Copy the captions ?>
    if (caption = $lnk.parent('.wp-caption').find('.wp-caption-text').html())
      $img.attr('title', caption);
    <?php EndIf; ?>
    
    <?php If ($wp_plugin_fancy_gallery->get_option('change_image_display')) : ?>
    $img.css('display', 'inline-block');
    <?php EndIf; ?>
    
    // Copy the title tag from link to img
    $lnk.attr('title', $img.attr('title'));
  });
  
  jQuery('a.fancybox')
  .unbind('click')
  .fancybox({  
    padding         :  <?php Echo IntVal($wp_plugin_fancy_gallery->get_option('border_width', 10)) ?>,
    cyclic          :  <?php Echo $wp_plugin_fancy_gallery->get_option('cyclic') ? 'true' : 'false' ?>,
    scrolling       : '<?php Echo $wp_plugin_fancy_gallery->get_option('scrolling', 'auto') ?>',
    centerOnScroll  :  <?php Echo $wp_plugin_fancy_gallery->get_option('center_on_scroll') ? 'true' : 'false' ?>,
    overlayOpacity  :  <?php Echo Round($wp_plugin_fancy_gallery->get_option('overlay_opacity', 30)) / 100 ?>,
    overlayColor    : '<?php Echo $wp_plugin_fancy_gallery->get_option('overlay_color', '#666') ?>',
    titleShow       :  <?php Echo ($wp_plugin_fancy_gallery->get_option('use_as_image_title')=='none') ? 'false' : 'true' ?>,
    titlePosition   : '<?php Echo $wp_plugin_fancy_gallery->get_option('title_position', 'float') ?>',
    transitionIn    : '<?php Echo $wp_plugin_fancy_gallery->get_option('transition_in', 'fade') ?>',
    transitionOut   : '<?php Echo $wp_plugin_fancy_gallery->get_option('transition_out', 'fade') ?>',    
    speedIn         :  <?php Echo IntVal($wp_plugin_fancy_gallery->get_option('speed_in', 300)) ?>,
    speedOut        :  <?php Echo IntVal($wp_plugin_fancy_gallery->get_option('speed_out', 300)) ?>,
    changeSpeed     :  <?php Echo IntVal($wp_plugin_fancy_gallery->get_option('change_speed', 300)) ?>,
    showCloseButton :  <?php Echo $wp_plugin_fancy_gallery->get_option('hide_close_button') ? 'false' : 'true' ?>    
  });

  jQuery('a.fancyframe')
  .unbind('click')
  .fancybox({
    padding         :  <?php Echo IntVal($wp_plugin_fancy_gallery->get_option('border_width', 10)) ?>,
    cyclic          :  <?php Echo $wp_plugin_fancy_gallery->get_option('cyclic') ? 'true' : 'false' ?>,
    scrolling       : '<?php Echo $wp_plugin_fancy_gallery->get_option('scrolling', 'auto') ?>',
    centerOnScroll  :  <?php Echo $wp_plugin_fancy_gallery->get_option('center_on_scroll') ? 'true' : 'false' ?>,
    overlayOpacity  :  <?php Echo Round($wp_plugin_fancy_gallery->get_option('overlay_opacity', 30)) / 100 ?>,
    overlayColor    : '<?php Echo $wp_plugin_fancy_gallery->get_option('overlay_color', '#666') ?>',
    speedIn         :  <?php Echo IntVal($wp_plugin_fancy_gallery->get_option('speed_in', 300)) ?>,
    speedOut        :  <?php Echo IntVal($wp_plugin_fancy_gallery->get_option('speed_out', 300)) ?>,
    showCloseButton :  <?php Echo $wp_plugin_fancy_gallery->get_option('hide_close_button') ? 'false' : 'true' ?>,
    height          : '75%',    
    width           : '75%',
    type            : 'iframe'    
  });

});
