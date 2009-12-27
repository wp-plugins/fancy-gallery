<?php

/*

Plugin Name: Fancy Gallery
Description: Will bring your galleries as valid XHTML blocks on screen and associate linked images with Fancybox. 
Version: 1.1
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
    Add_Action ('admin_head', Array($this, 'remove_gallery_settings'));
    
    // Add jQuery
    wp_enqueue_script('jquery');
  }

  Function filter_gallery_shortcode ($_, $attr){
    GLOBAL $post;
    
    $attr = Array_Merge(Array(
      'post_parent'    => $post->ID,
      'post_status'    => 'inherit',
      'post_type'      => 'attachment',
      'post_mime_type' => 'image',
      'order'          => 'ASC',
      'orderby'        => 'menu_order',
      'size'           => 'thumbnail',
      'link'           => 'file', // nothing else make sense
      'include'        => '',
      'exclude'        => '' ),
      $attr);

  	// get attachments
    If (!Empty($attr['include']))
      // this gallery only includes images
      $attachments = get_posts(Array(
        'include'        => $attr['include'],
        'post_status'    => $attr['post_status'],
        'post_type'      => $attr['post_type'],
        'post_mime_type' => $attr['post_mime_type'],
        'order'          => $attr['order'],
        'orderby'        => $attr['orderby'] )); 
  	Else
  	  // this gallery uses the post attachments
      $attachments = get_children(Array(
        'post_parent'    => $attr['post_parent'],
        'exclude'        => $attr['exclude'],
        'post_status'    => $attr['post_status'],
        'post_type'      => $attr['post_type'],
        'post_mime_type' => $attr['post_mime_type'],
        'order'          => $attr['order'],
        'orderby'        => $attr['orderby'] ));
  	
  	// There are no attachments
  	If (Empty($attachments)) return False;

  	$code = '<div class="gallery" id="gallery_'.$post->ID.'">';
  	
    ForEach ($attachments AS $id => $attachment)
      $code .= wp_get_attachment_link($attachment->ID, $attr['size']);
      
    $code .= '</div>';
    
  	return $code;
  }
  
  Function remove_gallery_settings(){
    ?><script type="text/javascript">jQuery(function(){
    
    jQuery('table#basic tr:eq(0), table#basic tr:eq(3)').hide();
    
    });</script><?php
  }
  
  Function include_fancy_image_box(){
    ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/jquery.fancybox-1.2.6.css" />
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.fancybox-1.2.6.pack.js"></script>
    <script type="text/javascript" src="<?php echo $this->base_url ?>/fancy.js"></script>
    <?php
  }



} /* End of the Class */
New wp_plugin_fancy_gallery();
} /* End of the If-Class-Exists-Condition */
/* End of File */