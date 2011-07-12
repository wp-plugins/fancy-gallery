<?php

/*

Plugin Name: Fancy Gallery
Description: Fancy Gallery converts your galleries to valid XHTML blocks and associates linked images with the Fancy Light Box.
Plugin URI: http://dennishoppe.de/wordpress-plugins/fancy-gallery 
Version: 1.3.32
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de

*/


If (!Class_Exists('wp_plugin_fancy_gallery')){
Class wp_plugin_fancy_gallery {
  var $base_url;
  var $version = '1.3.32';
  
  Function __construct(){
    // Read base
    $this->base_url = get_bloginfo('wpurl').'/'.Str_Replace("\\", '/', SubStr(RealPath(DirName(__FILE__)), Strlen(ABSPATH)));
    
    // Get ready to translate
    Add_Action ('init', Array($this, 'Load_TextDomain'));
    
    // Set Hooks
    Add_Action ('admin_menu', Array($this, 'Add_Options_Page'));
    Add_ShortCode ('gallery', Array($this, 'gallery_shortcode'));
        
    // Add Scripts & Styles
    If (Is_Admin()) {
      Add_Action ('media_upload_gallery', Array($this, 'Media_Upload_Tab'));
      Add_Action ('post-upload-ui', Array($this, 'Print_Pro_Banner'));
    }
    Else {
      WP_Enqueue_Script('fancybox', $this->base_url . '/fancybox/jquery.fancybox-1.3.4.pack.js', Array('jquery'), '1.3.4' );
      WP_Enqueue_Script('jquery.easing', $this->base_url . '/jquery.easing.1.3.js', Array('jquery'), '1.3' );
      WP_Enqueue_Script('jquery.mousewheel', $this->base_url . '/jquery.mousewheel-3.0.4.pack.js', Array('jquery'), '3.0.4' );
      WP_Enqueue_Script('fancy-gallery', $this->base_url . '/fancy-js.php', Array('jquery', 'fancybox'), $this->version, ($this->get_option('script_position') != 'header') );
      WP_Enqueue_Style('fancybox', $this->base_url . '/fancybox/jquery.fancybox-1.3.4.css', Array(), '1.3.4');
      WP_Enqueue_Style('fancybox-ie-fix', $this->base_url . '/fancybox/jquery.fancybox-1.3.4.css-png-fix.php');
      WP_Enqueue_Style('fancy-gallery', $this->base_url . '/fancy-gallery.css');      
    }
    
    // Add this to GLOBALS
    $GLOBALS[__CLASS__] = $this;
  }

  Function Load_TextDomain(){
    $locale = Apply_Filters( 'plugin_locale', get_locale(), __CLASS__ );
    Load_TextDomain (__CLASS__, DirName(__FILE__).'/language/' . $locale . '.mo');
  }
  
  Function t ($text, $context = ''){
    // Translates the string $text with context $context
    If ($context == '')
      return Translate ($text, __CLASS__);
    Else
      return Translate_With_GetText_Context ($text, $context, __CLASS__);
  }
  
  Function Media_Upload_Tab(){
    WP_Enqueue_Script('fancy-gallery-media-upload', $this->base_url . '/media-upload.js', Array('jquery') );
  }
  
  Function Add_Options_Page(){
    $handle = Add_Options_Page(
      $this->t('Fancy Gallery Settings'),
      $this->t('Fancy Gallery'),
      'manage_options',
      __CLASS__,
      Array($this, 'Print_Options_Page')
    );

    Add_Action ('load-' . $handle, Array($this, 'Load_Options_Page'));
  }

  Function Load_Options_Page(){
    WP_Enqueue_Script('farbtastic');
    WP_Enqueue_Style('farbtastic');        

    WP_Enqueue_Script('fancy-gallery-options-page', $this->base_url . '/options-page.js', Array('jquery') );
    WP_Enqueue_Style('fancy-gallery-options-page', $this->base_url . '/options-page.css' );
  }
  
  Function Print_Options_Page(){
    ?><div class="wrap">
      <?php screen_icon(); ?>
      <h2><?php Echo $this->t('Fancy Gallery Options') ?></h2>
      
      <form method="post" action="">
        
        <?php If (!Empty($_POST) && $this->Save_Options()) : ?>
          <div id="message" class="updated fade"><p><strong><?php _e('Settings saved.') ?></strong></p></div>
        <?php EndIf; ?>
        
        <?php Include DirName(__FILE__).'/options-page.php' ?>
        
        <div style="max-width:600px">
          <?php do_action('dh_contribution_message') ?>
        </div>
        
        <p class="submit">
          <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
      </form>

    </div><?php
  }

  Function Save_Options(){
    // If there is no post data we bail out
    If (Empty($_POST)) return False;
    
    // Save options
    Update_Option (__CLASS__, stripslashes_deep($_POST));
    
    // Everything is ok =)
    return True;
  }

  Function get_option ($key = Null, $default = False){
    Static $settings;
    If (!IsSet($settings)) $settings = (Array) get_option(__CLASS__);
    
    If ( IsSet($settings[$key]) && $settings[$key] != '' )
      // the setting were saved and there is a value
      return $settings[$key];
    Else
      return $default;

  }
  
  Function get_image_title($attachment){
    If (!Is_Object($attachment)) return False;
    
    // Image title
    $image_title = $attachment->post_title;
    
    // Alternative Text
    $alternative_text = Get_Post_Meta($attachment->ID, '_wp_attachment_image_alt', True);
    If (Empty($alternative_text)) $alternative_text = $image_title;
    
    // Image caption
    $caption = $attachment->post_excerpt;
    If (Empty($caption)) $caption = $image_title;
    
    // Image description
    $description = nl2br($attachment->post_content);
    $description = Str_Replace ("\n", '', $description);
    $description = Str_Replace ("\r", '', $description);
    If (Empty($description)) $description = $caption;
    
    // return Title
    Switch (self::get_option('use_as_image_title')){
      Case 'none': return False;
      Case 'alt_text': return $alternative_text;
      Case 'caption': return $caption;
      Case 'description': return $description;
      Default: return $image_title;
    }
  }

  Function gallery_shortcode ($attr){
    $attr = Array_Merge(Array(
      'id'             => $GLOBALS['post']->ID,
      'post_status'    => 'inherit',
      'post_type'      => 'attachment',
      'post_mime_type' => 'image',
      'order'          => 'ASC',
      'orderby'        => 'menu_order',
      'size'           => 'thumbnail',
      'link'           => 'file', // nothing else make sense
      'include'        => '',
      'exclude'        => '' ),
      (array) $attr);

  	// get attachments
    If (Empty($attr['include'])) // this gallery uses the post attachments
      $attachments = get_children(Array(
        'post_parent'    => $attr['id'],
        'exclude'        => $attr['exclude'],
        'post_status'    => $attr['post_status'],
        'post_type'      => $attr['post_type'],
        'post_mime_type' => $attr['post_mime_type'],
        'order'          => $attr['order'],
        'orderby'        => $attr['orderby'] ));

  	Else // this gallery only includes images
      $attachments = get_posts(Array(
        'include'        => $attr['include'],
        'post_status'    => $attr['post_status'],
        'post_type'      => $attr['post_type'],
        'post_mime_type' => $attr['post_mime_type'],
        'order'          => $attr['order'],
        'orderby'        => $attr['orderby'] )); 
  	
  	// There are no attachments
  	If (Empty($attachments)) return False;
  	
  	// Gallery box
  	$code = '<div class="fancy-gallery gallery" id="gallery_' . $attr['id'] . '">';
  	
  	// Build the HTML Code
    ForEach ($attachments AS $id => $attachment){
      // Thumb URL, width, height
      List($src, $width, $height) =  wp_get_attachment_image_src($attachment->ID, $attr['size']);
      
      // Image title
      $title = HTMLSpecialChars($this->get_image_title($attachment));
      
      // CSS Class
      $class = Array('attachment-' . $attr['size']);
      
      // Run filter
      $html_attributes = Apply_Filters( 'wp_get_attachment_image_attributes', Array(
        'src' => $src,
        'width' => $width,
        'height' => $height,
        'class' => Implode(' ', $class),
        'alt' => $title,
        'title' => $title
      ), $attachment );
      
      // Buld IMG HTML Code
      $code .= '<a href="' . wp_get_attachment_url($attachment->ID) . '" title="' . $title . '"><img ';
      ForEach ((ARRAY)$html_attributes AS $attribute => $value) $code .= $attribute . '="' . $value . '" ';      
      $code .= '/></a>';
    }
    
    // End of the gallery box
    $code .= '<div class="clear"></div>';
    $code .= '</div>';
    
  	return $code;
  }
  
  Function Print_Pro_Banner(){
    ?>
    <p><a href="http://wpplugins.com/plugin/870/fancy-gallery-pro" target="_blank"><img src="http://wpplugins.com/wp-content/uploads/projectimages/870/c540d672dc13f2d59d8fd5ace7d40d5f.png" alt="Fancy Gallery Pro"></a></p>
    <?php
  }
  
} /* End of the Class */
New wp_plugin_fancy_gallery();
Require_Once DirName(__FILE__).'/contribution.php';
} /* End of the If-Class-Exists-Condition */
/* End of File */