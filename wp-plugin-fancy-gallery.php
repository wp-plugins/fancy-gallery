<?php

/*

Plugin Name: Fancy Gallery
Description: Will bring your galleries as valid XHTML blocks on screen and associate linked images with Fancybox.
Plugin URI: http://dennishoppe.de/wordpress-plugins/fancy-gallery 
Version: 1.3.2
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de

*/


// Include Donate Plugin
Require_Once DirName(__FILE__).'/donate.php';
    

If (!Class_Exists('wp_plugin_fancy_gallery')){
Class wp_plugin_fancy_gallery {
  var $base_url;
  var $settingskey;
  var $text_domain;
  
  Function __construct(){
    // Read base
    $this->base_url = get_bloginfo('wpurl').'/'.Str_Replace("\\", '/', SubStr(RealPath(DirName(__FILE__)), Strlen(ABSPATH)));
    
    // Settings key
    $this->settingskey = get_class($this);
  
    // Get ready to translate
    $this->Load_TextDomain();
    
    // Set Hooks
    If ( Is_Admin() ){
      Add_Action ('admin_menu', Array($this, 'add_settings_menu'));
      Add_Action ('admin_head', Array($this, 'admin_additional_header'));
    }
    Add_Action ('wp_head', Array($this, 'include_fancy_image_box'));
    Add_Filter ('post_gallery', Array($this, 'filter_gallery_shortcode'), 10, 2 );
    
    // Add jQuery
    wp_enqueue_script('jquery');
  }

  Function filter_gallery_shortcode ($_, $attr){
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
        'post_parent'    => $attr['id'],
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
  
  Function admin_additional_header(){
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
  
  Function include_fancy_image_box(){
    ?>
    <!-- Fancy Gallery (WordPress Plugin by Dennis Hoppe) Components -->
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="<?php echo $this->base_url ?>/jquery.mousewheel-3.0.2.pack.js"></script>
    
    <link type="text/css" rel="stylesheet" href="<?php echo $this->base_url?>/fancybox/jquery.fancybox-1.3.1.css" />
    <script type="text/javascript" src="<?php echo $this->base_url ?>/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
    
    <script type="text/javascript" src="<?php echo $this->base_url ?>/fancy-js.php?class=<?php echo get_class($this) ?>&amp;key=<?php Echo $this->settingskey ?>"></script>
    <!-- End of Fancy Gallery Components -->
    <?php
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
  
  Function add_settings_menu(){
    Add_Options_Page(
      $this->t('Fancy Gallery Settings'),
      $this->t('Fancy Gallery'),
      8,
      get_class($this),
      Array($this, 'print_options_page')
    );
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
            <input type="checkbox" name="cyclic" value="yes" <?php Checked ($this->LoadSetting('cyclic'), 'yes') ?>/>            
            <?php Echo $this->t('Will enable the user to get from the last image to the first one with the next button.') ?>
          </td>
        </tr>
        
        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Scrollbars') ?></th>
          <td>
            <select name="scrolling">
              <option value="auto" <?php Selected ($this->LoadSetting('scrolling'), 'auto') ?> ><?php Echo $this->t('Automatic') ?></option>
              <option value="yes" <?php Selected ($this->LoadSetting('scrolling'), 'yes') ?> ><?php _e('Yes') ?></option>
              <option value="no" <?php Selected ($this->LoadSetting('scrolling'), 'no') ?> ><?php _e('No') ?></option>
            </select>
            (<?php Echo $this->t('"Automatic" means scrollbars will be visibly if necessary. "Yes" and "No" should be clear.') ?>)
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Center on scroll') ?></th>
          <td>
            <input type="checkbox" name="center_on_scroll" value="yes" <?php Checked ($this->LoadSetting('center_on_scroll'), 'yes') ?>/>            
            <?php Echo $this->t('Keep the FancyBox always in the center of the screen while scrolling the page.') ?>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Overlay opacity') ?></th>
          <td>
            <input type="text" name="overlay_opacity" value="<?php Echo $this->LoadSetting('overlay_opacity') ?>" size="3" />%<br />            
            <small><?php Echo $this->t('Percentaged opacity of the background of the FancyBox. Should be a value from 0 to 100. (Default is 30)') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Overlay color') ?></th>
          <td>
            <div id="colorpicker"></div>
            <input type="text" name="overlay_color" value="<?php Echo $this->LoadSetting('overlay_color') ?>" class="color" /><br />            
            <small><?php Echo $this->t('Please choose the color of the "darker" background.') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Image title') ?></th>
          <td>
            <input type="checkbox" name="show_title" value="yes" <?php Checked ($this->LoadSetting('show_title'), 'yes') ?>/>            
            <?php Echo $this->t('Tick this box if the image title should be visible to the user.') ?>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Title position') ?></th>
          <td>
            <select name="title_position">
              <option value="inside" <?php Selected ($this->LoadSetting('title_position'), 'inside') ?> ><?php Echo $this->t('Inside the FancyBox') ?></option>
              <option value="outside" <?php Selected ($this->LoadSetting('title_position'), 'outside') ?> ><?php Echo $this->t('Outside the FancyBox') ?></option>
              <option value="over" <?php Selected ($this->LoadSetting('title_position'), 'over') ?> ><?php Echo $this->t('Over the image') ?></option>
            </select>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Opening transition') ?></th>
          <td>
            <select name="transition_in">
              <option value="elastic" <?php Selected ($this->LoadSetting('transition_in'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
              <option value="fade" <?php Selected ($this->LoadSetting('transition_in'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
              <option value="none" <?php Selected ($this->LoadSetting('transition_in'), 'none') ?> ><?php Echo $this->t('No transition') ?></option>
            </select>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Opening speed') ?></th>
          <td>
            <input type="text" name="speed_in" value="<?php Echo $this->LoadSetting('speed_in') ?>" size="3" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
            <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Closing transition') ?></th>
          <td>
            <select name="transition_out">
              <option value="elastic" <?php Selected ($this->LoadSetting('transition_out'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
              <option value="fade" <?php Selected ($this->LoadSetting('transition_out'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
              <option value="none" <?php Selected ($this->LoadSetting('transition_out'), 'none') ?> ><?php Echo $this->t('No transition') ?></option>
            </select>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Closing speed') ?></th>
          <td>
            <input type="text" name="speed_out" value="<?php Echo $this->LoadSetting('speed_out') ?>" size="3" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
            <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
          </td>
        </tr>

        <tr valign="top">
          <th scope="row"><?php Echo $this->t('Image resizing speed') ?></th>
          <td>
            <input type="text" name="change_speed" value="<?php Echo $this->LoadSetting('change_speed') ?>" size="3" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
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
    
    // Change the overlay_opacity input field
    If (IsSet($_POST['overlay_opacity'])){
      $_POST['overlay_opacity'] = IntVal($_POST['overlay_opacity']);
      If ($_POST['overlay_opacity'] < 0 || $_POST['overlay_opacity'] > 100)
        Unset ($_POST['overlay_opacity']);
    }
    Else {
      Unset ($_POST['overlay_opacity']);
    }
    
    // Save options
    Update_Option ($this->settingskey, stripslashes_deep($_POST));
  }

  Function LoadSetting ($key = Null){
    Static $settings;
    If (!IsSet($settings)) $settings = Array_Merge ($this->default_settings(), (Array) get_option($this->settingskey));
    
    If ($key == Null)
      // return all saved settings
      return $settings;
    ElseIf ( IsSet ($settings[$key]) && $settings[$key] != Null && $settings[$key] !== False && $settings[$key] != '' )
      // the setting were saved and there is a value
      return $settings[$key];
    Else {
      // there no settings behind this key or the field is empty
      $default = $this->default_settings();
      If (IsSet($default[$key])) return $default[$key];
      Else return False;
    }
  }
  
  Function default_settings(){
    return Array(
      'cyclic' => 'no',
      'scrolling' => 'auto',
      'center_on_scroll' => 'no',
      'overlay_opacity' => 30,
      'overlay_color' => '#606060',
      'show_title' => 'no',
      'title_position' => 'outside',
      'transition_in' => 'fade',
      'transition_out' => 'fade',
      'speed_in' => 300,
      'speed_out' => 300,
      'change_speed' => 300      
    );
  }


} /* End of the Class */
New wp_plugin_fancy_gallery();
} /* End of the If-Class-Exists-Condition */
/* End of File */