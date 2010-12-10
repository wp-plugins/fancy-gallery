<?php

/*

Plugin Name: Fancy Gallery
Description: Fancy Gallery converts your galleries to valid XHTML blocks and associates linked images with the Fancy Light Box.
Plugin URI: http://dennishoppe.de/wordpress-plugins/fancy-gallery 
Version: 1.3.17
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de

*/


// Please think about a donation
If (Is_File(DirName(__FILE__).'/donate.php')) Include DirName(__FILE__).'/donate.php';
    

If (!Class_Exists('wp_plugin_fancy_gallery')){
Class wp_plugin_fancy_gallery {
  var $base_url;
  var $text_domain;
  
  Function wp_plugin_fancy_gallery(){
    // PHP4 Constructor, show PHP4 Warning message to user
    Add_Action('admin_notices', Create_Function('','
      Echo "<div class=\"error\"><p><b>Error: Fancy Gallery requires PHP5. Your WordPress runs with PHP4.</b></p></div>";
    '));
  }
  
  Function __construct(){
    // Read base
    $this->base_url = $this->get_base_url();
    
    // Get ready to translate
    $this->Load_TextDomain();
    
    // Set Hooks
    Add_Action ('admin_head', Array($this, 'print_admin_header'));
    Add_Action ('admin_menu', Array($this, 'add_options_page'));
    Add_Action ('wp_head', Array($this, 'print_header'));
    Add_ShortCode ('gallery', Array($this, 'gallery_shortcode'));
        
    // Add jQuery
    wp_enqueue_script('jquery');
  }

  Function Load_TextDomain(){
    $this->text_domain = get_class($this);
    load_textdomain ($this->text_domain, DirName(__FILE__) . '/language/' . get_locale() . '.mo');
  }
  
  Function t ($text, $context = ''){
    // Translates the string $text with context $context
    If ($context == '')
      return __($text, $this->text_domain);
    Else
      return _x($text, $context, $this->text_domain);
  }
  
  Function get_base_url(){ return get_bloginfo('wpurl').'/'.Str_Replace("\\", '/', SubStr(RealPath(DirName(__FILE__)), Strlen(ABSPATH))); }
  
  Function setting_key(){ return __CLASS__; }

  Function add_options_page(){
    $handle = Add_Options_Page(
      $this->t('Fancy Gallery Settings'),
      $this->t('Fancy Gallery'),
      8,
      __CLASS__,
      Array($this, 'print_options_page_body')
    );

    Add_Action ('admin_head-' . $handle, Array($this, 'print_options_page_head'));
  }

  Function print_options_page_head(){
    ?>   
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/colorpicker/farbtastic.css" />
    <script type="text/javascript" src="<?php echo $this->base_url ?>/colorpicker/farbtastic.js"></script>    
    <script type="text/javascript" src="<?php echo $this->base_url?>/options_page.js"></script>
    <?php
  }
  
  Function print_options_page_body(){
    ?><div class="wrap">
      <?php screen_icon(); ?>
      <h2><?php Echo $this->t('Fancy Gallery Options') ?></h2>
      
      <form method="post" action="">
        
        <?php If (!Empty($_POST) && $this->Save_Options()) : ?>
          <div id="message" class="updated fade"><p><strong><?php _e('Settings saved.') ?></strong></p></div>
        <?php EndIf; ?>
        
        <?php Include DirName(__FILE__).'/options_page.php' ?>
        
        <div style="max-width:600px">
          <?php do_action('donation_message') ?>
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
    Update_Option ($this->setting_key(), stripslashes_deep($_POST));
    
    // Everything is ok =)
    return True;
  }

  Function get_option ($key = Null, $default = False){
    Static $settings;
    If (!IsSet($settings)) $settings = (Array) get_option(self::setting_key());
    
    If ( IsSet($settings[$key]) && $settings[$key] != '' )
      // the setting were saved and there is a value
      return $settings[$key];
    Else
      return $default;

  }
  
  Function print_admin_header(){
    ?>
    
    <!-- Fancy Gallery (WordPress Plugin by Dennis Hoppe) Components -->
    <script type="text/javascript" src="<?php echo $this->base_url ?>/admin.js"></script>
    <!-- End of Fancy Gallery Components -->
    
    <?php
  }
  
  Function print_header(){
    ?>
    
    <!-- Fancy Gallery (WordPress Plugin by Dennis Hoppe) Components -->
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.mousewheel-3.0.2.pack.js"></script>
    
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/fancybox/jquery.fancybox-1.3.1.css" />
    <!--[if lt IE 7]>
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/fancybox/jquery.fancybox-1.3.1.css-png-fix.php" />
    <![endif]-->
    <script type="text/javascript" src="<?php echo $this->base_url ?>/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
    
    <script type="text/javascript" src="<?php echo $this->base_url ?>/fancy-js.php"></script>    
    <!-- End of Fancy Gallery Components -->
    
    <?php
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
  	$code = '<div class="fancy gallery" id="gallery_' . $attr['id'] . '">';
  	
  	// Build the HTML Code
    ForEach ($attachments AS $id => $attachment){
      // Thumb URL, width, height
      List($src, $width, $height) =  wp_get_attachment_image_src($attachment->ID, $attr['size']);
      
      // Image title
      $title = HTMLSpecialChars($this->get_image_title($attachment));
      
      // CSS Class
      $class = 'attachment-' . $attr['size'];
      
      // Run filter
      $html_attributes = Apply_Filters( 'wp_get_attachment_image_attributes', Array(
        'src' => $src,
        'width' => $width,
        'height' => $height,
        'class' => $class,
        'alt' => $title,
        'title' => $title
      ), $attachment );
      
      // Buld IMG HTML Code
      $code .= '<a href="' . wp_get_attachment_url($attachment->ID) . '" title="' . $title . '"><img ';
      ForEach ((ARRAY)$html_attributes AS $attribute => $value) $code .= $attribute . '="' . $value . '" ';      
      $code .= '/></a>';
    }
    
    // End of the gallery box
    $code .= '</div>';
    
  	return $code;
  }
  
} /* End of the Class */
New wp_plugin_fancy_gallery();
} /* End of the If-Class-Exists-Condition */
/* End of File */