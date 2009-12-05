<?php

/*

Plugin Name: Fancy Gallery
Description: Will bring your galleries as valid XHTML blocks on screen and associate linked images with Fancybox. 
Version: 1.0
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de

*/


If (!Class_Exists('wp_plugin_fancy_gallery')){
Class wp_plugin_fancy_gallery {
  var $base_url;
  
  Function wp_plugin_fancy_gallery(){
    // Read base
    $this->base_url = get_option('home').'/'.Str_Replace("\\", '/', SubStr(  RealPath(DirName(__FILE__)), Strlen(ABSPATH) ));
    
    // Set Hooks
    Add_Action ('wp_head', Array($this, 'include_fancy_image_box'));
    Add_Filter ('post_gallery', Array($this, 'filter_gallery_shortcode'), 10, 2 );
    Add_Action ('admin_head', Array($this, 'add_admin_stylesheet'));
    
    // Add jquery to every page
    wp_enqueue_script('jquery');
  }

  Function filter_gallery_shortcode ($_, $attr){
    GLOBAL $post;

  	$attachments = get_children(Array(
      'post_parent' => $post->ID,
      'post_status' => 'inherit',
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'order' => 'ASC',
      'orderby' => 'menu_order' ));

  	$code = '<div class="gallery" id="gallery_'.$post->ID.'">';
  	
    ForEach ($attachments AS $id => $attachment)
      $code .= wp_get_attachment_link($attachment->ID, 'thumbnail');
      
    $code .= '</div>';
    
  	return $code;
  }
  
  Function add_admin_stylesheet(){
    Echo '<link rel="stylesheet" type="text/css" href="'.$this->base_url.'/admin.css" />';
  }
  
  Function include_fancy_image_box(){
    ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/jquery.fancybox-1.2.6.css" />
    <script type="text/javascript" src="<?php echo $this->base_url?>/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo $this->base_url?>/jquery.fancybox-1.2.6.pack.js"></script>
    <script type="text/javascript" src="<?php echo $this->base_url?>/fancy.js"></script>
    <?php
  }



} /* End of the Class */
New wp_plugin_fancy_gallery();
} /* End of the If-Class-Exists Condition */
/* End of File */