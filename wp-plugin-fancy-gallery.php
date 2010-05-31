<?php

/*

Plugin Name: Fancy Gallery
Description: Will bring your galleries as valid XHTML blocks on screen and associate linked images with Fancybox.
Plugin URI: http://dennishoppe.de/wordpress-plugins/fancy-gallery 
Version: 1.3.4
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de

*/


// Include Donate Plugin
Require_Once DirName(__FILE__).'/donate.php';
    

If (!Class_Exists('wp_plugin_fancy_gallery')){
Class wp_plugin_fancy_gallery {
  var $base_url;
  var $text_domain;
  
  Function __construct(){
    // Read base
    $this->base_url = get_bloginfo('wpurl').'/'.Str_Replace("\\", '/', SubStr(RealPath(DirName(__FILE__)), Strlen(ABSPATH)));
    
    // Get ready to translate
    $this->Load_TextDomain();
    
    // Set Hooks
    If ( Is_Admin() ){
      Add_Action ('admin_menu', Array($this, 'add_options_page'));
      Add_Action ('admin_head', Array($this, 'print_admin_header'));
    }
    Else {
      Add_Action ('wp_head', Array($this, 'print_header'));
      Add_ShortCode ('gallery', Array($this, 'gallery_shortcode'));
    }
    
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
  
  Function setting_key(){ return __CLASS__; }

  Function add_options_page(){
    Add_Options_Page(
      $this->t('Fancy Gallery Settings'),
      $this->t('Fancy Gallery'),
      8,
      get_class($this),
      Array($this, 'print_options_page')
    );    
  }

  Function print_admin_header(){
    ?>
    
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/colorpicker/farbtastic.css" />
    <script type="text/javascript" src="<?php echo $this->base_url ?>/colorpicker/farbtastic.js"></script>
    
    <script type="text/javascript">
    jQuery(function($){
      
      // Hide the senseless gallery setting fields
      jQuery('table#basic tr:eq(0), table#basic tr:eq(3)').hide();
      
    });
    </script>
    
    <?php
  }
  
  Function print_options_page(){ ?>
    <script type="text/javascript">
    jQuery(function($){
      
      // Activate the color picker
      jQuery('#colorpicker').farbtastic('input.color');
      
    });
    </script>

    <div class="wrap">
      <?php screen_icon(); ?>
      <h2><?php Echo $this->t('Fancy Gallery Settings') ?></h2>
      
      <form method="post" action="">
        
        <?php If (!Empty($_POST) && $this->Save_Settings()) : ?>
          <div id="message" class="updated fade"><p><strong><?php _e('Settings saved.') ?></strong></p></div>
        <?php EndIf; ?>
        

        <table class="form-table">
        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Loop mode') ?></th>
          <td>
            <input type="checkbox" name="cyclic" value="yes" <?php Checked ($this->Load_Setting('cyclic'), 'yes') ?>/>            
            <?php Echo $this->t('Will enable the user to get from the last image to the first one with the next button.') ?>
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Scrollbars') ?></th>
          <td>
            <select name="scrolling">
              <option value=""><?php Echo $this->t('Automatic') ?></option>
              <option value="yes" <?php Selected ($this->Load_Setting('scrolling'), 'yes') ?> ><?php _e('Yes') ?></option>
              <option value="no" <?php Selected ($this->Load_Setting('scrolling'), 'no') ?> ><?php _e('No') ?></option>
            </select>
            (<?php Echo $this->t('"Automatic" means scrollbars will be visibly if necessary. "Yes" and "No" should be clear.') ?>)
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Center on scroll') ?></th>
          <td>
            <input type="checkbox" name="center_on_scroll" value="yes" <?php Checked ($this->Load_Setting('center_on_scroll'), 'yes') ?>/>            
            <?php Echo $this->t('Keep the FancyBox always in the center of the screen while scrolling the page.') ?>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Overlay opacity') ?></th>
          <td>
            <input type="text" name="overlay_opacity" value="<?php Echo IntVal($this->Load_Setting('overlay_opacity', 30)) ?>" size="3" />%<br />            
            <small><?php Echo $this->t('Percentaged opacity of the background of the FancyBox. Should be a value from 0 (invisible) to 100 (opaque). (Default is 30)') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Overlay color') ?></th>
          <td>
            <div id="colorpicker"></div>
            <input type="text" name="overlay_color" value="<?php Echo $this->Load_Setting('overlay_color', '#666') ?>" class="color" /><br />            
            <small><?php Echo $this->t('Please choose the color of the "darker" background.') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Image title') ?></th>
          <td>
            <input type="checkbox" name="hide_image_title" value="yes" <?php Checked ($this->Load_Setting('hide_image_title'), 'yes') ?>/>            
            <?php Echo $this->t('Do not shot image titles.') ?>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Title position') ?></th>
          <td>
            <select name="title_position">
              <option value="outside" <?php Selected ($this->Load_Setting('title_position'), 'outside') ?> ><?php Echo $this->t('Outside the FancyBox') ?></option>
              <option value="inside" <?php Selected ($this->Load_Setting('title_position'), 'inside') ?> ><?php Echo $this->t('Inside the FancyBox') ?></option>
              <option value="over" <?php Selected ($this->Load_Setting('title_position'), 'over') ?> ><?php Echo $this->t('Over the image') ?></option>
            </select>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Opening transition') ?></th>
          <td>
            <select name="transition_in">
              <option value="fade" <?php Selected ($this->Load_Setting('transition_in'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
              <option value="elastic" <?php Selected ($this->Load_Setting('transition_in'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
              <option value="none" <?php Selected ($this->Load_Setting('transition_in'), 'none') ?> ><?php Echo $this->t('No transition') ?></option>
            </select>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Opening speed') ?></th>
          <td>
            <input type="text" name="speed_in" value="<?php Echo IntVal($this->Load_Setting('speed_in', 300)) ?>" size="4" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
            <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Closing transition') ?></th>
          <td>
            <select name="transition_out">
              <option value="fade" <?php Selected ($this->Load_Setting('transition_out'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
              <option value="elastic" <?php Selected ($this->Load_Setting('transition_out'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
              <option value="none" <?php Selected ($this->Load_Setting('transition_out'), 'none') ?> ><?php Echo $this->t('No transition') ?></option>
            </select>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Closing speed') ?></th>
          <td>
            <input type="text" name="speed_out" value="<?php Echo IntVal($this->Load_Setting('speed_out', 300)) ?>" size="4" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
            <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Image resizing speed') ?></th>
          <td>
            <input type="text" name="change_speed" value="<?php Echo IntVal($this->Load_Setting('change_speed', 300)) ?>" size="4" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
            <small><?php Echo $this->t('Speed of resizing when changing gallery items. (in milliseconds)') ?></small>
          </td>
        </tr>
        
        </table>
        
        <div style="max-width:600px">
          <?php do_action('donation_message') ?>
        </div>
        
        <p class="submit">
          <input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>
      </form>

    </div><?php
  }

  Function Save_Settings(){
    // If there is no post data we bail out
    If (Empty($_POST)) return False;
    
    // Save options
    Update_Option (self::setting_key(), stripslashes_deep($_POST));
    
    // Everything is ok =)
    return True;
  }

  Function Load_Setting ($key = Null, $default = False){
    Static $settings;
    If (!IsSet($settings)) $settings = (Array) get_option(self::setting_key());
    
    If ( IsSet($settings[$key]) && $settings[$key] != '' )
      // the setting were saved and there is a value
      return $settings[$key];
    Else
      return $default;

  }
  
  Function print_header(){
    ?>
    <!-- Fancy Gallery (WordPress Plugin by Dennis Hoppe) Components -->
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.mousewheel-3.0.2.pack.js"></script>
    
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/fancybox/jquery.fancybox-1.3.1.css" />
    <script type="text/javascript" src="<?php echo $this->base_url ?>/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
    
    <script type="text/javascript" src="<?php echo $this->base_url ?>/fancy-js.php?class=<?php echo get_class($this) ?>"></script>
    <!-- End of Fancy Gallery Components -->
    <?php
  }

  Function gallery_shortcode ($attr){
    GLOBAL $post;
    
    $attr = Array_Merge(Array(
      'id'             => $post->ID,
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

  	$code = '<div class="gallery" id="gallery_'.$post->ID.'">';
  	
    ForEach ($attachments AS $id => $attachment)
      $code .= wp_get_attachment_link($attachment->ID, $attr['size']);
      
    $code .= '</div>';
    
  	return $code;
  }
  
} /* End of the Class */
New wp_plugin_fancy_gallery();
} /* End of the If-Class-Exists-Condition */
/* End of File */