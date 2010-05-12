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
Function LoadSetting ($key = Null){
  Static $settings;
  Static $defaults;
  
  If (!IsSet($settings)) $settings = (Array) get_option($_REQUEST['key']);
  If (!IsSet($defaults)) $defaults = (Array) call_user_func(Array($_REQUEST['class'], 'default_settings'));
  
  If ( IsSet ($settings[$key]) && $settings[$key] != Null && $settings[$key] !== False && $settings[$key] != '' )
    return $settings[$key];

  ElseIf ( IsSet($defaults[$key]) )
    return $defaults[$key];

  Else
    return False;
}


// Load WordPress
If (!$wordpress = FindWordPress())
  Die('Could not load WordPress.');
Else
  Include_Once $wordpress;


// Load settings
If (!IsSet($_REQUEST['class'])) Die ('No WordPress plugin class name broadcasted.');
If (!IsSet($_REQUEST['key'])) Die ('No WordPress option key broadcasted.');

// Set image extensions
$arr_type = Array( 'jpg', 'jpeg', 'png', 'gif', 'bmp' );


?>

jQuery(function(){

  // group gallery items
  jQuery('div.gallery a').each(function(){
    jQuery(this).attr('rel', jQuery(this).parent().attr('id'));
  });
    
  // Add Fancy Classes to single items:
  jQuery('a').each(function(){
    // filter items
    if ( <?php ForEach ($arr_type AS $type) : ?>this.href.substr(-<?php Echo StrLen($type)+1 ?>).toLowerCase().indexOf('.<?php Echo $type ?>') < 0 && <?php EndForEach; ?>true )
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
    cyclic         :  <?php Echo LoadSetting('cyclic') == 'yes' ? 'true' : 'false' ?>,
    scrolling      : '<?php Echo LoadSetting('scrolling') ?>',
    centerOnScroll :  <?php Echo LoadSetting('center_on_scroll') == 'yes' ? 'true' : 'false' ?>,
    overlayOpacity :  <?php Echo Round(LoadSetting('overlay_opacity')) / 100 ?>,
    overlayColor   : '<?php Echo LoadSetting('overlay_color') ?>',
    titleShow      :  <?php Echo LoadSetting('show_title') == 'yes' ? 'true' : 'false' ?>,
    titlePosition  : '<?php Echo LoadSetting('title_position') ?>',
    transitionIn   : '<?php Echo LoadSetting('transition_in') ?>',
    transitionOut  : '<?php Echo LoadSetting('transition_out') ?>',    
    speedIn        :  <?php Echo Round(LoadSetting('speed_in')) ?>,
    speedOut       :  <?php Echo Round(LoadSetting('speed_out')) ?>,
    changeSpeed    :  <?php Echo Round(LoadSetting('change_speed')) ?>
    
  });

});