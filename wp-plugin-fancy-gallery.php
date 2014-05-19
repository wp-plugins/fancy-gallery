<?php
/*
Plugin Name: Fancy Gallery Lite
Plugin URI: http://dennishoppe.de/en/wordpress-plugins/fancy-gallery
Description: Fancy Gallery Lite enables you to create galleries and converts your galleries in post and pages to valid HTML blocks and associates linked images with the Fancy Light Box.
Version: 1.4.4
Author: Dennis Hoppe
Author URI: http://DennisHoppe.de
*/

Include DirName(__FILE__).'/wp-widget-fancy-random-images.php';
Include DirName(__FILE__).'/wp-widget-fancy-taxonomies.php';
Include DirName(__FILE__).'/wp-widget-fancy-taxonomy-cloud.php';

If (!Class_Exists('wp_plugin_fancy_gallery')){
class wp_plugin_fancy_gallery {
  var $base_url; # url to the plugin directory
  var $version = '1.4.3'; # Current release number
  var $arr_option_box; # Meta boxes for the option page
  var $arr_gallery_meta_box; # Meta boxes for the gallery post type
  var $arr_taxonomies; # All buildIn Gallery Taxonomies - also the inactive ones.
  var $gallery_post_type = 'fancy-gallery'; # Name of the gallery post type
  var $gallery; # The current gallery object while running shortcode

  function __construct(){
    # Read base
    $this->Load_Base_Url();

    # Option boxes
    $this->arr_option_box = Array( 'main' => Array(), 'side' => Array() );

    # Meta Boxes
    $this->arr_gallery_meta_box = Array();

    # Template directory
    $this->template_dir = WP_CONTENT_DIR . '/fancy-gallery-templates';

    # Get ready to translate
    Add_Action('widgets_init', Array($this, 'Load_TextDomain'));

    # This Plugin supports post thumbnails
    Add_Theme_Support('post-thumbnails');

    # Set Hooks
    Register_Activation_Hook(__FILE__, Array($this, 'Plugin_Activation'));
    Add_Action('admin_menu', Array($this, 'Add_Options_Page'));
    Add_Filter('post_class', Array($this, 'Filter_Post_Class'));
    Add_Action('wp_enqueue_scripts', Array($this, 'enqueue_frontend_scripts'));
    Add_Action('admin_enqueue_scripts', Array($this, 'enqueue_admin_scripts'));

    #If ($this->get_option('gallery_management') == 'yes'){
      Add_Action('init', Array($this, 'Register_Gallery_Post_Type'));
      Add_Action('init', Array($this, 'Register_Gallery_Taxonomies'));
      Add_Action('admin_init', Array($this, 'Add_Taxonomy_Archive_Urls'), 99);
      Add_Filter('image_upload_iframe_src', Array($this, 'Image_Upload_Iframe_Src'));
      Add_Action('admin_init', Array($this, 'Add_GetTextFilter'), 99);
      Add_Action('widgets_init', Array($this, 'Register_Widgets'));
      Add_Filter('post_updated_messages', Array($this, 'Gallery_Updated_Messages' ));
      Add_Filter('the_content', Array($this, 'Filter_Content'), 9  );
      Add_Filter('the_content_feed', Array($this, 'Filter_Feed_Content'));
      Add_Filter('the_excerpt_rss', Array($this, 'Filter_Feed_Content'));
      Add_Action('media_upload_gallery', Array($this, 'Media_Upload_Tab') );
      Add_Action('save_post', Array($this, 'Save_Meta_Box'), 10, 2);
      Add_Action('admin_init', Array($this, 'User_Creates_New_Gallery'));
      Add_Action('untrash_post', Array($this, 'User_Untrashes_Post'));
      Add_Filter('views_edit-fancy-gallery', Array($this, 'Add_Gallery_Count_Notice'));
      Add_Action('admin_print_styles', Array($this, 'Print_Dashboard_Styles'));
      Add_Action('admin_bar_menu', Array($this, 'Filter_Admin_Bar_Menu'), 999);
    #}

    #Add_ShortCode('gallery', Array($this, 'ShortCode_Gallery'));

    If (IsSet($_REQUEST['strip_tabs'])){
      Add_Action('media_upload_gallery', Array($this, 'Add_Media_Upload_Style'));
      Add_Action('media_upload_image', Array($this, 'Add_Media_Upload_Style'));
      Add_Filter('media_upload_tabs', Array($this, 'Media_Upload_Tabs'));
      Add_Filter('media_upload_form_url', Array($this, 'Media_Upload_Form_URL'));
      Add_Action('media_upload_import_images', Array($this, 'Import_Images'));
    }

    If (!$this->get_option('disable_excerpts')){
      Add_Filter('get_the_excerpt', Array($this, 'Filter_Excerpt'), 9 );
    }

    # Add to GLOBALs
    $GLOBALS[__CLASS__] = $this;
  }

  function Plugin_Activation(){
    $this->Load_TextDomain();
    $this->Register_Gallery_Post_Type();
    Flush_Rewrite_Rules();
  }

  function Load_Base_Url(){
    $absolute_plugin_folder = RealPath(DirName(__FILE__));

    If (SubStr($absolute_plugin_folder, 0, Strlen(ABSPATH)) == ABSPATH)
      $this->base_url = get_bloginfo('wpurl').'/'.SubStr($absolute_plugin_folder, Strlen(ABSPATH));
    Else
      $this->base_url = Plugins_Url(BaseName(DirName(__FILE__)));

    $this->base_url = Str_Replace("\\", '/', $this->base_url); # Windows Workaround
  }

  function Load_TextDomain(){
    $locale = Apply_Filters( 'plugin_locale', get_locale(), __CLASS__ );
    Load_TextDomain (__CLASS__, DirName(__FILE__).'/language/' . $locale . '.mo');
  }

  function t ($text, $context = ''){
    # Translates the string $text with context $context
    If ($context == '')
      return Translate ($text, __CLASS__);
    Else
      return Translate_With_GetText_Context ($text, $context, __CLASS__);
  }

  function Register_Widgets(){
    Register_Widget('wp_widget_fancy_random_images');
    Register_Widget('wp_widget_fancy_taxonomies');
    Register_Widget('wp_widget_fancy_taxonomy_cloud');
  }

  function Enqueue_Frontend_Scripts(){
    # Prepare JS_OPTIONS
    $JS_OPTIONS = $this->Get_Option();

    # Enqueue the lightbox library
    If ($this->Get_Option('lightbox') == 'fancybox1') $this->Enqueue_Fancybox_1();
    ElseIf ($this->Get_Option('lightbox') == 'fancybox2') $this->Enqueue_Fancybox_2();

    WP_Enqueue_Script('fancy-gallery', $this->base_url . '/js/fancy-gallery.js', Array('jquery'), $this->version, ($this->get_option('script_position') != 'header') );

    # Enqueue Template Stylesheets
    $JS_OPTIONS['templates'] = Array();
    ForEach ($this->Get_Template_Files() AS $template_name => $template_properties){
      $style_sheet_name = BaseName($template_properties['file'], '.php') . '.css';
      $style_sheet_file = DirName($template_properties['file']) . '/' . $style_sheet_name;
      If (!Is_File($style_sheet_file)) Continue;
      $template_dir = DirName($style_sheet_file);
      $style_sheet_id = 'fancy-gallery-template-'.Sanitize_Title($template_name);

      If (StrPos($template_dir, DirName(__FILE__)) === 0){
        $template_base_url = $this->base_url . SubStr($template_dir, StrLen(DirName(__FILE__)));
      }
      Else {
        $template_base_url = Get_Bloginfo('wpurl').'/'.SubStr($template_dir, Strlen(ABSPATH));
      }

      $template_base_url = Str_Replace("\\", '/', $template_base_url); # Windows workaround
      #WP_Enqueue_Style($style_sheet_id, $template_base_url . '/' . $style_sheet_name);
      $JS_OPTIONS['templates'][] = $template_base_url . '/' . $style_sheet_name;
    }

    # Add the plugin options to the JS front end
    WP_Localize_Script('fancy-gallery', 'FANCY_GALLERY', $JS_OPTIONS);
  }

  function Enqueue_Admin_Scripts(){
    #WP_Enqueue_Style('fancy-gallery-icon', $this->base_url . '/fancy-gallery-icon.css');
    WP_Enqueue_Script('jquery-livequery', $this->base_url.'/js/jquery.livequery.min.js', Array('jquery'), '1.3.6', True);
    WP_Enqueue_Script('fancy-gallery-media-gallery-settings', $this->base_url . '/js/gallery-settings.js', Array('jquery', 'jquery-livequery'), Null, True );
  }

  function Enqueue_Fancybox_1(){
    # Enqueue Fancybox 1.3.x
    WP_Enqueue_Script('jquery-mousewheel', $this->base_url . '/js/jquery.mousewheel.min.js', Array('jquery'), '3.1.11', ($this->get_option('script_position') != 'header') );
    WP_Enqueue_Script('fancybox', $this->base_url . '/fancybox-v1/jquery.fancybox-1.3.4.pack.js', Array('jquery', 'jquery-mousewheel'), '1.3.4', ($this->get_option('script_position') != 'header') );
    WP_Enqueue_Style('fancybox', $this->base_url . '/fancybox-v1/jquery.fancybox-1.3.4.css', Null, '1.3.4');
  }

  function Enqueue_Fancybox_2(){
    /* Fancybox 2 @ CDNJS
     * //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js
     * //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css
     * //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.js
     * //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.css
     * //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-media.js
     * //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.js
     * //cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.css
    */

    # Enqueue Fancybox 2.x
    WP_Enqueue_Script('jquery-mousewheel', $this->base_url . '/js/jquery.mousewheel.min.js', Array('jquery'), '3.1.11', ($this->get_option('script_position') != 'header') );
    WP_Enqueue_Script('fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js', Array('jquery', 'jquery-mousewheel'), '2.1.5', ($this->get_option('script_position') != 'header'));
    WP_Enqueue_Script('fancybox-buttons', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.js', Array('fancybox'), '2.1.5', ($this->get_option('script_position') != 'header'));
    WP_Enqueue_Script('fancybox-media', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-media.js', Array('fancybox'), '2.1.5', ($this->get_option('script_position') != 'header'));
    WP_Enqueue_Script('fancybox-thumbs', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.js', Array('fancybox'), '2.1.5', ($this->get_option('script_position') != 'header'));
    WP_Enqueue_Style('fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css', Null, '2.1.5');
    WP_Enqueue_Style('fancybox-buttons', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-buttons.css', Null, '2.1.5');
    WP_Enqueue_Style('fancybox-thumbs', '//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/helpers/jquery.fancybox-thumbs.css', Null, '2.1.5');
    WP_Enqueue_Style('fancybox-pachtes', $this->base_url.'/fancybox-v1/jquery.fancybox.patches.css', Array('fancybox', 'fancybox-buttons', 'fancybox-thumbs'), $this->version);
  }

  function Add_GetTextFilter(){
    Global $pagenow;
    If (($pagenow == 'async-upload.php' || $pagenow == 'media-upload.php')){
      If (IsSet($_REQUEST['post_id'])){
        $post = Get_Post(IntVal($_REQUEST['post_id']));
      }
      ElseIf (IsSet($_REQUEST['attachment_id'])){
        $attachment = Get_Post(IntVal($_REQUEST['attachment_id']));
        $post = Get_Post($attachment->post_parent);
      }

      If (IsSet($post->post_type) && $post->post_type == $this->gallery_post_type)
        Add_Filter ( 'gettext', Array($this, 'Filter_GetText'), 10, 3 );
    }
  }

  function Filter_GetText($translation, $text, $domain = 'default'){
    If ($domain == 'default'){
      $arr_replace = Array(
        'Set featured image' => $this->t('Set Gallery Thumbnail'),
        'Remove featured image' => $this->t('Remove Gallery Thumbnail'),
        'Use as featured image' => $this->t('Use as Gallery Thumbnail')
      );
      If (IsSet($arr_replace[$text])) return $arr_replace[$text];
    }
    return $translation;
  }

  function Media_Upload_Tab(){
    WP_Enqueue_Script('fancy-gallery-media-upload', $this->base_url . '/media-upload.js', Array('jquery') );
  }

  function Field_Name($option_name){
    # Generates field names for the meta box
    return __CLASS__ . '[' . $option_name . ']';
  }

  function Save_Meta_Box($post_id, $post){
    # If this is an autosave we dont care
    If ( Defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

    # Check the PostType
    If ($post->post_type != $this->gallery_post_type) return;

    # Check if this request came from the edit page section
    If (IsSet($_POST[ __CLASS__ ]))
      # Save Meta data
      Update_Post_Meta($post_id, '_' . __CLASS__, (Array) ($_POST[__CLASS__]));
  }

  function Get_Gallery_Meta ($key = Null, $default = False, $post_id = Null){
    # Get the post id
    If ($post_id == Null && Is_Object($GLOBALS['post']))
      $post_id = $GLOBALS['post']->ID;
    ElseIf ($post_id == Null && !Is_Object($GLOBALS['post']))
      return False;

    # Read meta data
    $arr_meta = get_post_meta($post_id, '_' . __CLASS__, True);
    If (Empty($arr_meta) || !Is_Array($arr_meta)) $arr_meta = Array();

    # Clean Meta data
    ForEach ($arr_meta AS $k => $v)
      If (!$v) Unset ($arr_meta[$k]);

    # Load default Meta data
    $arr_meta = Array_Merge ( $this->Default_Meta(), $arr_meta );

    # Get the key value
    If ($key == Null)
      return $arr_meta;
    ElseIf (IsSet($arr_meta[$key]) && $arr_meta[$key])
      return $arr_meta[$key];
    Else
      return $default;
  }

  function Add_Options_Page(){
    $handle = Add_Options_Page (
      $this->t('Fancy Gallery Options'),
      $this->t('Fancy Gallery'),
      'manage_options',
      __CLASS__,
      Array($this, 'Print_Options_Page')
    );

    # Add JavaScript to this handle
    Add_Action ('load-' . $handle, Array($this, 'Load_Options_Page'));

    # Add option boxes
    $this->Add_Option_Box ($this->t('Lightbox'), DirName(__FILE__).'/options-page/option-box-lightbox.php');
    #$this->Add_Option_Box ($this->t('Gallery Management'), DirName(__FILE__).'/options-page/option-box-gallery-management.php', 'side');

    #If ($this->get_option('gallery_management') == 'yes'){
      $this->Add_Option_Box ($this->t('Templates'), DirName(__FILE__).'/options-page/option-box-templates.php', 'main', 'closed');
      $this->Add_Option_Box ($this->t('User rights'), DirName(__FILE__).'/options-page/option-box-capabilities.php', 'main', 'closed');

      $this->Add_Option_Box ($this->t('Taxonomies'), DirName(__FILE__).'/options-page/option-box-taxonomies.php', 'side');
      $this->Add_Option_Box ($this->t('Archive Url'), DirName(__FILE__).'/options-page/option-box-archive-link.php', 'side');
    #}

    $this->Add_Option_Box ($this->t('Miscellaneous'), DirName(__FILE__).'/options-page/option-box-misc.php', 'side');

  }

  function Get_Options_Page_Url($parameters = Array()){
    $url = Add_Query_Arg(Array('page' => __CLASS__), Admin_Url('options-general.php'));
    If (Is_Array($parameters) && !Empty($parameters)) $url = Add_Query_Arg($parameters, $url);
    return $url;
  }

  function Load_Options_Page(){
    # If the Request was redirected from a "Save Options"-Post
    If (IsSet($_GET['options_saved'])) Flush_Rewrite_Rules();

    # If this is a Post request to save the options
    If ($this->Save_Options()) WP_Redirect( $this->Get_Options_Page_Url(Array('options_saved' => 'true')) );

    WP_Enqueue_Script('dashboard');
    WP_Enqueue_Style('dashboard');

    #WP_Enqueue_Script('farbtastic');
    #WP_Enqueue_Style('farbtastic');

    WP_Enqueue_Script('fancy-gallery-options-page', $this->base_url . '/options-page/options-page.js', Array('jquery'), Null, True);
    WP_Enqueue_Style('fancy-gallery-options-page', $this->base_url . '/options-page/options-page.css' );

    # Remove incompatible JS Libs
    WP_Dequeue_Script('post');
  }

  function Print_Options_Page(){
    $options_saved = IsSet($_GET['options_saved']) && !Empty($_GET['options_saved']);
    ?>
    <div class="wrap">
      <h2><?php Echo $this->t('Fancy Gallery Options') ?></h2>

      <?php If ($options_saved) : ?>
      <div id="message" class="updated fade">
        <p><strong><?php _e('Settings saved.') ?></strong></p>
      </div>
      <?php EndIf; ?>

      <form method="post" action="" enctype="multipart/form-data">
      <div class="metabox-holder">

        <div class="postbox-container left">
          <?php ForEach ($this->arr_option_box['main'] AS $box) : ?>
            <div class="postbox should-be-<?php Echo $box['state'] ?>">
              <div class="handlediv" title="Click to toggle"><br /></div>
              <h3 class="hndle"><span><?php Echo $box['title'] ?></span></h3>
              <div class="inside"><?php Include $box['file'] ?></div>
            </div>
          <?php EndForEach ?>
        </div>

        <div class="postbox-container right">
          <?php ForEach ($this->arr_option_box['side'] AS $box) : ?>
            <div class="postbox should-be-<?php Echo $box['state'] ?>">
              <div class="handlediv" title="Click to toggle"><br /></div>
              <h3 class="hndle"><span><?php Echo $box['title'] ?></span></h3>
              <div class="inside"><?php Include $box['file'] ?></div>
            </div>
          <?php EndForEach ?>
        </div>
      </div>

      <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>">
      </p>

      </form>
    </div>
    <?php
  }

  function Add_Option_Box($title, $include_file, $column = 'main', $state = 'opened'){
    # Check the input
    If (!Is_File($include_file)) return False;
    If ( $title == '' ) $title = '&nbsp;';

    # Column (can be 'side' or 'main')
    If ($column != '' && $column != Null && $column != 'main')
      $column = 'side';
    Else
      $column = 'main';

    # State (can be 'opened' or 'closed')
    If ($state != '' && $state != Null && $state != 'opened')
      $state = 'closed';
    Else
      $state = 'opened';

    # Add a new box
    $this->arr_option_box[$column][] = Array('title' => $title, 'file' => $include_file, 'state' => $state);
  }

  function Save_Options(){
    # Check if this is a post request
    If (Empty($_POST)) return False;

    # Clean the Post array
    $_POST = StripSlashes_Deep($_POST);
    ForEach ($_POST AS $option => $value)
      If (!$value) Unset ($_POST[$option]);

    # Save Options
    Update_Option (__CLASS__, $_POST);

    return True;
  }

  function Default_Options(){
    return Array(
      'lightbox' => 'fancybox1',

      'loop' => False,
      'padding' => 10, # Space inside fancyBox around content
      'hide_close_button' => False,
      'auto_play' => False, # If set to true, slideshow will start after opening the first gallery item
      'play_speed' => 2750, # Slideshow speed in milliseconds
      'open_effect' => 'elastic', # 'elastic', 'fade' or 'none'
      'open_speed' => 300, # in ms
      'close_effect' => 'elastic', # 'elastic', 'fade' or 'none'
      'close_speed' => 300, # in ms
      'change_effect' => 'elastic', # 'elastic', 'fade' or 'none'
      'change_speed' => 300, # in ms
      'title_position' => 'float', # 'float', 'inside', 'outside' or 'over'
      'controls_position' => 'top', # 'top', 'bottom' or 'none'
      'thumbs_position' => 'bottom', # 'top', 'bottom' or 'none'
      'use_as_image_title' => 'title',

      'gallery_taxonomy' => Array(),

      'disable_excerpts' => False,
      'excerpt_thumb_width' => get_option('thumbnail_size_w'),
      'excerpt_thumb_height' => get_option('thumbnail_size_h'),
      'excerpt_image_number' => 3,

      'deactivate_archive' => False,

      'change_image_display' => False,

      'gallery_management' => 'yes'
    );
  }

  function Default_Meta(){
    return Array(
      'excerpt_type' => 'images',
      'thumb_width' => get_option('thumbnail_size_w'),
      'thumb_height' => get_option('thumbnail_size_h'),
      'excerpt_image_number' => $this->Get_Option('excerpt_image_number'),
      'excerpt_thumb_width' => $this->get_option('excerpt_thumb_width'),
      'excerpt_thumb_height' => $this->get_option('excerpt_thumb_height')
    );
  }

  function Get_Option($key = Null, $default = False){
    # Read Options
    $arr_option = Array_Merge (
      (Array) $this->Default_Options(),
      (Array) get_option(__CLASS__)
    );

    # Locate the option
    If ($key == Null)
      return $arr_option;
    ElseIf (IsSet($arr_option[$key]))
      return $arr_option[$key];
    Else
      return $default;
  }

  function Register_Gallery_Post_Type(){
    # Register Product Post Type
    Register_Post_Type ($this->gallery_post_type, Array(
      'labels' => Array(
        'name' => $this->t('Galleries'),
        'singular_name' => $this->t('Gallery'),
        'add_new' => $this->t('Add Gallery'),
        'add_new_item' => $this->t('New Gallery'),
        'edit_item' => $this->t('Edit Gallery'),
        'view_item' => $this->t('View Gallery'),
        'search_items' => $this->t('Search Galleries'),
        'not_found' =>  $this->t('No Galleries found'),
        'not_found_in_trash' => $this->t('No Galleries found in Trash'),
        'parent_item_colon' => ''
        ),
      'public' => True,
      'show_ui' => True,
      'has_archive' => !$this->get_option('deactivate_archive'),
      'capability_type' => Array('post', 'posts'),
			'map_meta_cap' => True,
			'hierarchical' => False,
      'rewrite' => Array(
        'slug' => $this->t('galleries', 'URL slug'),
        'with_front' => False
      ),
      'supports' => Array( 'title', 'author', 'excerpt', 'thumbnail', 'comments' ),
      'menu_position' => 10, # below Media
      'menu_icon' => 'dashicons-images-alt',
      'register_meta_box_cb' => Array($this, 'Add_Gallery_Meta_Boxes')
    ));
  }

  function Gallery_Updated_Messages($arr_message){
    return Array_Merge ($arr_message, Array($this->gallery_post_type => Array(
      1 => SPrintF ($this->t('Gallery updated. (<a href="%s">View Gallery</a>)'), get_permalink()),
      2 => __('Custom field updated.'),
      3 => __('Custom field deleted.'),
      4 => $this->t('Gallery updated.'),
      5 => IsSet($_GET['revision']) ? SPrintF($this->t('Gallery restored to revision from %s'), WP_Post_Revision_Title( (Int) $_GET['revision'], False ) ) : False,
      6 => SPrintF($this->t('Gallery published. (<a href="%s">View Gallery</a>)'), get_permalink()),
      7 => $this->t('Gallery saved.'),
      8 => $this->t('Gallery submitted.'),
      9 => SPrintF($this->t('Gallery scheduled. (<a target="_blank" href="%s">View Gallery</a>)'), get_permalink()),
      10 => SPrintF($this->t('Gallery draft updated. (<a target="_blank" href="%s">Preview Gallery</a>)'), Add_Query_Arg('preview', 'true', get_permalink()))
    )));
  }

  function Get_Gallery_Taxonomies(){
    return Array(
      'gallery_category' => Array(
        'label' => $this->t( 'Gallery Categories' ),
        'labels' => Array(
          'name' => $this->t( 'Categories' ),
          'singular_name' => $this->t( 'Category' ),
          'search_items' =>  $this->t( 'Search Categories' ),
          'all_items' => $this->t( 'All Categories' ),
          'parent_item' => $this->t( 'Parent Category' ),
          'parent_item_colon' => $this->t( 'Parent Category:' ),
          'edit_item' => $this->t( 'Edit Category' ),
          'update_item' => $this->t( 'Update Category' ),
          'add_new_item' => $this->t( 'Add New Category' ),
          'new_item_name' => $this->t( 'New Category' )
        ),
        'show_admin_column' => True,
        'hierarchical' => True,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => $this->t('gallery-category', 'URL slug')
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_categories',
          'edit_terms' => 'manage_categories',
          'delete_terms' => 'manage_categories',
          'assign_terms' => 'edit_posts'
        )
      ),

      'gallery_tag' => Array(
        'label' => $this->t( 'Gallery Tags' ),
        'labels' => Array(
          'name' => $this->t( 'Tags' ),
          'singular_name' => $this->t( 'Tag' ),
          'search_items' =>  $this->t( 'Search Tags' ),
          'all_items' => $this->t( 'All Tags' ),
          'edit_item' => $this->t( 'Edit Tag' ),
          'update_item' => $this->t( 'Update Tag' ),
          'add_new_item' => $this->t( 'Add New Tag' ),
          'new_item_name' => $this->t( 'New Tag' )
        ),
        'show_admin_column' => True,
        'hierarchical' => False,
        'show_ui' => True,
        'query_var' => True,
        'rewrite' => Array(
          'with_front' => False,
          'slug' => $this->t('gallery-tag', 'URL slug')
        ),
        'capabilities' => Array (
          'manage_terms' => 'manage_categories',
          'edit_terms' => 'manage_categories',
          'delete_terms' => 'manage_categories',
          'assign_terms' => 'edit_posts'
        )
      ),
    );
  }

  function Register_Gallery_Taxonomies(){
    # Load Taxonomies
    $this->arr_taxonomies = $this->Get_Gallery_Taxonomies();

    # Register Taxonomies
    ForEach ( (Array) $this->get_option('gallery_taxonomies') As $taxonomie => $attributes ){
      If (!IsSet($this->arr_taxonomies[$taxonomie])) Continue;
      Register_Taxonomy ($taxonomie, $this->gallery_post_type, Array_Merge($this->arr_taxonomies[$taxonomie], $attributes));
    }
  }

  function Add_Taxonomy_Archive_Urls(){
    ForEach(Get_Object_Taxonomies($this->gallery_post_type) AS $taxonomy){ /*$taxonomy = Get_Taxonomy($taxonomy)*/
      Add_Action ($taxonomy.'_edit_form_fields', Array($this, 'Print_Taxonomy_Archive_Urls'), 10, 3);
    }
  }

  function Print_Taxonomy_Archive_Urls($tag, $taxonomy){
    $taxonomy = Get_Taxonomy($taxonomy);
    $archive_url = get_term_link(get_term($tag->term_id, $taxonomy->name));
    $archive_feed = get_term_feed_link($tag->term_id, $taxonomy->name);
    ?>
    <tr class="form-field">
      <th scope="row" valign="top"><?php Echo $this->t('Archive Url') ?></th>
      <td>
        <code><a href="<?php Echo $archive_url ?>" target="_blank"><?php Echo $archive_url ?></a></code><br />
        <span class="description"><?php PrintF($this->t('This is the URL to the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
      </td>
    </tr>
    <tr class="form-field">
      <th scope="row" valign="top"><?php Echo $this->t('Archive Feed') ?></th>
      <td>
        <code><a href="<?php Echo $archive_feed ?>" target="_blank"><?php Echo $archive_feed ?></a></code><br />
        <span class="description"><?php PrintF($this->t('This is the URL to the feed of the archive of this %s.'), $taxonomy->labels->singular_name) ?></span>
      </td>
    </tr>
    <?php
  }

  function Add_Media_Upload_Style(){
    WP_Enqueue_Style('fancy-gallery-media-upload', $this->base_url . '/media-upload.css');
  }

  function Media_Upload_Tabs($arr_tabs){
    return Array(
      'type' => $this->t('Upload Images'),
      'gallery' => $arr_tabs['gallery'],
      'import_images' => $this->t('Import from Library')
    );
  }

  function Media_Upload_Form_URL($url){
    return $url . '&strip_tabs=true';
  }

  function Image_Upload_Iframe_Src($url){
    If ($GLOBALS['post']->post_type == $this->gallery_post_type)
      return $url . '&strip_tabs=true';
    Else
      return $url;
  }

  function Filter_Excerpt($excerpt){
    If ( $GLOBALS['post']->post_type == $this->gallery_post_type && $this->Get_Gallery_Meta('excerpt_type') == 'images' ){
      return $this->ShortCode_Gallery(Array(
        'number'          => $this->Get_Gallery_Meta('excerpt_image_number'),
        'orderby'         => 'rand',
        'thumb_width'     => $this->Get_Gallery_Meta('excerpt_thumb_width'),
        'thumb_height'    => $this->Get_Gallery_Meta('excerpt_thumb_height'),
        'thumb_grayscale' => $this->Get_Gallery_Meta('excerpt_thumb_grayscale'),
        'thumb_negate'    => $this->Get_Gallery_Meta('excerpt_thumb_negate'),
        'template'        => $this->Get_Gallery_Meta('excerpt_template')
      ));
    }
    Else return $excerpt;
  }

  function Filter_Content($content){
    If ( $GLOBALS['post']->post_type == $this->gallery_post_type &&
         StrPos($content, '[gallery]') === False &&
         StrPos($content, '[gallery ') === False &&
         !post_password_required() ){
      return $content . $this->ShortCode_Gallery();
    }
    Else return $content;
  }

  function Filter_Feed_Content($content){
    If ( $GLOBALS['post']->post_type == $this->gallery_post_type){
      return $this->ShortCode_Gallery(Array(
        'number'          => $this->Get_Gallery_Meta('excerpt_image_number'),
        'orderby'         => 'rand',
        'thumb_width'     => $this->Get_Gallery_Meta('excerpt_thumb_width'),
        'thumb_height'    => $this->Get_Gallery_Meta('excerpt_thumb_height'),
        'thumb_grayscale' => $this->Get_Gallery_Meta('excerpt_thumb_grayscale'),
        'thumb_negate'    => $this->Get_Gallery_Meta('excerpt_thumb_negate'),
        'template'        => $this->Get_Default_Feed_Template()
      ));
    }
    Else return $content;
  }

  function Filter_Post_Class($arr_class){
    $arr_class[] = 'fancy-gallery-content-unit';
    return $arr_class;
  }

  function Add_Gallery_Meta_Box($title, $include_file, $column = 'normal', $priority = 'default'){
    If (!$title) return False;
    If (!Is_File($include_file)) return False;
    If ($column != 'side') $column = 'normal';

    # Add to array
    $this->arr_gallery_meta_box[] = Array(
      'title' => $title,
      'include_file' => $include_file,
      'column' => $column,
      'priority' => $priority
    );
  }

  function Add_Gallery_Meta_Boxes(){
    Global $post_type_object;

    # Enqueue Edit Gallery JavaScript/CSS
    WP_Enqueue_Script('fancy-gallery-meta-boxes', $this->base_url . '/meta-boxes/meta-boxes.js', Array('jquery'), $this->version);
    WP_Enqueue_Style('fancy-gallery-meta-boxes', $this->base_url . '/meta-boxes/meta-boxes.css', False, $this->version);

    # Remove Meta Boxes
    Remove_Meta_Box('authordiv', $this->gallery_post_type, 'normal');
    Remove_Meta_Box('postexcerpt', $this->gallery_post_type, 'normal');

    # Change some core texts
    Add_Filter ( 'gettext', Array($this, 'Filter_GetText'), 10, 3 );

    # Register Meta Boxes
    $this->Add_Gallery_Meta_Box( $this->t('Images'), DirName(__FILE__) . '/meta-boxes/gallery-meta-box-images.php', 'normal', 'high' );

    If (!$this->get_option('disable_excerpts'))
      $this->Add_Gallery_Meta_Box( $this->t('Excerpt'), DirName(__FILE__) . '/meta-boxes/gallery-meta-box-excerpt.php', 'normal', 'high' );

    $this->Add_Gallery_Meta_Box( $this->t('Template'), DirName(__FILE__) . '/meta-boxes/gallery-meta-box-template.php', 'normal', 'high' );

    If (Current_User_Can($post_type_object->cap->edit_others_posts))
      $this->Add_Gallery_Meta_Box( $this->t('Owner'), DirName(__FILE__) . '/meta-boxes/gallery-meta-box-owner.php' );

    $this->Add_Gallery_Meta_Box( $this->t('Gallery ShortCode'), DirName(__FILE__) . '/meta-boxes/gallery-meta-box-show-code.php', 'side', 'high' );
    $this->Add_Gallery_Meta_Box( $this->t('Thumbnails'), DirName(__FILE__) . '/meta-boxes/gallery-meta-box-thumbnails.php', 'side' );

    # Add Meta Boxes
    ForEach ($this->arr_gallery_meta_box AS $box_index => $meta_box){
      Add_Meta_Box(
        BaseName($meta_box['include_file'], '.php'),
        $meta_box['title'],
        Array($this, 'Print_Gallery_Meta_Box'),
        $this->gallery_post_type,
        $meta_box['column'],
        $meta_box['priority'],
        $box_index
      );
    }
  }

  function Print_Gallery_Meta_Box($post, $box){
    $include_file = $this->arr_gallery_meta_box[$box['args']]['include_file'];
    If (Is_File ($include_file))
      Include $include_file;
  }

  function Import_Images(){
		# Enqueue Scripts and Styles
		WP_Enqueue_Style('media');
		WP_Enqueue_Style('import-images', $this->base_url.'/import-images-form.css', Null, $this->version);
		WP_Enqueue_Script('import-images', $this->base_url.'/import-images-form.js', Array('jquery'), $this->version, True);

		# Check if an attachment should be moved
		$message = '';
		If (IsSet($_REQUEST['move_attachment']) && IsSet($_REQUEST['move_to'])){
			$attachment_id = IntVal($_REQUEST['move_attachment']);
			$dst_post_id = IntVal($_REQUEST['move_to']);
			WP_Update_Post(Array(
				'ID' => $attachment_id,
				'post_parent' => $dst_post_id
			));
			$message = $this->t('The Attachment was moved to your gallery.');
		}

		# Generate Output
		return wp_iframe( Array($this, 'Print_Import_Images_Form'), $message );
	}

	function Print_Import_Images_Form($message = ''){
		Media_Upload_Header();
		Include DirName(__FILE__).'/import-images-form.php';
	}

  function Get_Image_Title($attachment){
    If (!Is_Object($attachment)) return False;

    # Image title
    $image_title = $attachment->post_title;

    # Alternative Text
    $alternative_text = Get_Post_Meta($attachment->ID, '_wp_attachment_image_alt', True);
    If (Empty($alternative_text)) $alternative_text = $image_title;

    # Image caption
    $caption = $attachment->post_excerpt;
    If (Empty($caption)) $caption = $image_title;

    # Image description
    $description = nl2br($attachment->post_content);
    $description = Str_Replace ("\n", '', $description);
    $description = Str_Replace ("\r", '', $description);
    If (Empty($description)) $description = $caption;

    # return Title
    Switch ($this->get_option('use_as_image_title')){
      Case 'none': return False;
      Case 'alt_text': return $alternative_text;
      Case 'caption': return $caption;
      Case 'description': return $description;
      Default: return $image_title;
    }
  }

  function Get_Template_Files(){
    $arr_template = Array_Unique(Array_Merge (
      (Array) Glob ( DirName(__FILE__) . '/templates/*.php' ),
      (Array) Glob ( DirName(__FILE__) . '/templates/*/*.php' ),

      (Array) Glob ( Get_StyleSheet_Directory() . '/*.php' ),
      (Array) Glob ( Get_StyleSheet_Directory() . '/*/*.php' ),

      Is_Child_Theme() ? (Array) Glob ( Get_Template_Directory() . '/*.php' ) : Array(),
      Is_Child_Theme() ? (Array) Glob ( Get_Template_Directory() . '/*/*.php' ) : Array(),

      (Array) Glob ( $this->template_dir . '/*.php' ),
      (Array) Glob ( $this->template_dir . '/*/*.php' )

    ));

    # Filter to add template files - you can use this filter to add template files to the user interface
    $arr_template = (Array) Apply_Filters('fancy_gallery_template_files', $arr_template);

    # Check if there template files
    If (Empty($arr_template)) return False;

    $arr_result = Array();
    $arr_sort = Array();
    ForEach ($arr_template AS $index => $template_file){
      # Read meta data from the template
      If (!$arr_properties = $this->Get_Template_Properties($template_file))
        Continue;
      Else {
        $arr_result[$arr_properties['name']] = Array_Merge ($arr_properties, Array('file' => $template_file));
        $arr_sort[$arr_properties['name']] = StrToLower($arr_properties['name']);
      }
    }
    Array_MultiSort($arr_sort, SORT_STRING, SORT_ASC, $arr_result);

    return $arr_result;
  }

  function Get_Template_Properties($template_file){
    # Check if this is a file
    If (!$template_file || !Is_File ($template_file) || !Is_Readable($template_file)) return False;

    # Read meta data from the template
    $arr_properties = Array_Merge(Get_File_Data ($template_file, Array(
      'name' => 'Fancy Gallery Template',
      'description' => 'Description',
      'author' => 'Author',
      'author_uri' => 'Author URI',
      'author_email' => 'Author E-Mail',
      'version' => 'Version'
    )), Array('file' => $template_file));

    # Check if there is a name for this template
    If (Empty($arr_properties['name']))
      return False;
    Else
      return $arr_properties;
  }

  function Get_Default_Template(){
    # Which file set the user as default?
    $template_file = $this->Get_Option('default_template_file');
    If (Is_File($template_file)) return $template_file;

    # Else:
    return DirName(__FILE__) . '/templates/default/gallery-default.php';
  }

  function Get_Default_Feed_Template(){
    return DirName(__FILE__) . '/templates/thumbnails-only/thumbnails-only.php.php';
  }

  function Generate_Gallery_Attributes($attributes = Array()){
    Global $post;

    If (!Is_Array($attributes)) $attributes = Array();

    # Get the Gallery Meta settings
    If (IsSet($attributes['id']) && !Empty($attributes['id']))
      $gallery_meta = $this->Get_Gallery_Meta(Null, False, $attributes['id']);
    Else {
      If ($post->post_type == $this->gallery_post_type){
        $attributes['id'] = $post->ID;
        $gallery_meta = $this->Get_Gallery_Meta(Null, False, $attributes['id']);
      }
      Else
        $gallery_meta = $this->Default_Meta();
    }

    # Merge Attributes
    $attributes = Array_Merge(Array(
      'id'              => $post->ID,
      'post_status'     => 'inherit',
      'post_type'       => 'attachment',
      'post_mime_type'  => 'image',
      'order'           => 'ASC',
      'orderby'         => (!IsSet($attributes['ids'])) ? 'menu_order' : 'post__in',
      'number'          => -1,
      'ids'             => '',
      'include'         => '',
      'exclude'         => '',
      'size'            => 'thumbnail',
      'link'            => 'file', # nothing else make sense
      'link_class'      => '',
      'thumb_width'     => '',
      'thumb_height'    => '',
      'thumb_grayscale' => False,
      'thumb_negate'    => False
    ), $gallery_meta, $attributes);

    # Rename some keys
    $attributes = Array_Merge($attributes, Array(
      'post_parent' => $attributes['id'],
      'posts_per_page' => $attributes['number'],
      'include' => $attributes['include'].$attributes['ids']
    ));

    # Edge case: Paramter "ids" is not empty
    If (!Empty($attributes['include'])) $attributes['post_parent'] = False;

    ForEach (Array('id', 'number', 'ids') AS $field)
      Unset($attributes[$field]);

    #PrintF('<pre>%s</pre>', Print_R ($attributes, True));

    return $attributes;
  }

  function Build_Gallery($arr_images, $attributes = Array()){
    # Prepare Gallery Array
    $this->gallery = New StdClass;
    $this->gallery->attributes = New StdClass;
    $this->gallery->images = Array();

    # Fill Attributes
    ForEach ((Array)$attributes AS $key => $value)
      $this->gallery->attributes->$key = $value;

    # Generate Gallery HTML ID
    If (!Empty($attributes['post_parent'])){ # this gallery uses the post attachments
      $this->gallery->id = $attributes['post_parent'];
    }
    Else { # this gallery only includes images
      $this->gallery->id = Str_Replace(',','-', $attributes['include']);
    }

  	# Build the Gallery object
    ForEach ($arr_images AS $id => &$image){
      # Thumb URL, width, height
      List($src, $width, $height) = wp_get_attachment_image_src($image->ID, $attributes['size']);

      $image->width = $width;
      $image->height = $height;
      $image->src = $src;

      # Image title
      $image->title = $this->get_image_title($image);

      # CSS Class
      $image->class = 'attachment-' . $attributes['size'];

      # Image Link
      If ($image->href == '') $image->href = WP_Get_Attachment_URL($image->ID);

      # Run filter
      $image->attributes = Apply_Filters( 'wp_get_attachment_image_attributes', Array(
        'src'    => $image->src,
        'width'  => $image->width,
        'height' => $image->height,
        'class'  => $image->class,
        'alt'    => $image->title,
        'title'  => $image->title
      ), $image );

      # Write in Object:
      $this->gallery->images[] = $image;
    }
  }

  function Render_Gallery ($template_file){
    # Uses template filter
    $template_file = Apply_Filters('fancy_gallery_template', $template_file);

    # If there is no valid template file we bail out
    If (!Is_File($template_file)) $template_file = $this->Get_Default_Template();

    # Load template
    Ob_Start();
    Include $template_file;
    $code = Ob_Get_Clean();

    # Strip Whitespaces
    $code = $this->Trim_HTML_Code($code);

    # Return
  	return $code;
  }

  function Trim_HTML_Code($html){
    $html = PReg_Replace('/\s+/', ' ', $html);
    $html = Str_Replace('> <', '><', $html);
    $html = Trim($html);
    return $html;
  }

  function ShortCode_Gallery ($attributes = Array()){
    $attributes = $this->Generate_Gallery_Attributes($attributes);

  	# Get Images
    #PrintF('<pre>%s</pre>', Print_R ($attributes, True));
    If (Empty($attributes['post_parent']) && Empty($attributes['include'])) return False;
    Else $arr_gallery = Get_Posts($attributes);

  	# There are no attachments
  	If (Empty($arr_gallery)) return False;

  	# Build the Gallery object
  	$this->Build_Gallery($arr_gallery, $attributes);

    # Load Template
    return $this->Render_Gallery($attributes['template']);
  }

  function Pro_Notice($message = 'feature', $output = True){
    $arr_message = Array(
      'feature' => $this->t('Available in the <a href="%s" target="_blank">Premium Version</a>.'),
      'custom_tax' => $this->t('Do you need a special taxonomy for your website? No problem! Just <a href="%s" target="_blank">get in touch</a>.'),
      'widget' => $this->t('This widget is available in the <a href="%s" target="_blank">Premium Version</a>. There will be no output in the front end of your website.'),
      'count_limit' => $this->t('In the <a href="%s" target="_blank">Premium Version of Fancy Gallery</a> you can take advantage of the gallery management without any limitations.'),
      'do_you_like' => $this->t('Do you like the gallery management tool? Upgrade to the <a href="%s" target="_blank">Premium Version of Fancy Gallery</a>!')
    );

    If (IsSet($arr_message[$message])){
      $message = SPrintF($arr_message[$message], $this->t('http://dennishoppe.de/en/wordpress-plugins/fancy-gallery', 'Link to the authors website'));
      If ($output) Echo $message;
      Else return $message;
    }
    Else
      return False;
  }

  function Count_Galleries(){
    Static $count;
    If ($count)
      return $count;
    Else
      return $count = Count(Get_Posts(Array('post_type' => $this->gallery_post_type, 'post_status' => 'any', 'numberposts' => -1)));
  }

  function User_Creates_New_Gallery(){
    If (BaseName($_SERVER['SCRIPT_NAME']) == 'post-new.php' && IsSet($_GET['post_type']) && $_GET['post_type'] == $this->gallery_post_type && !$this->Check_Gallery_Count())
      $this->Print_Gallery_Count_Limit();
  }

  function User_Untrashes_Post($post_id){
    If (Get_Post_Type($post_id) == $this->gallery_post_type && !$this->Check_Gallery_Count()) $this->Print_Gallery_Count_Limit();
  }

  function Check_Gallery_Count(){
    return $this->Count_Galleries() < 3;
  }

  function Print_Gallery_Count_Limit(){
    WP_Die(
      SPrintF('<p>%s</p><p>%s</p>',
        $this->Pro_Notice('count_limit', False),
        SPrintF('<a href="%s" class="button">%s</a>', Admin_URL('edit.php?post_type=' . $this->gallery_post_type), $this->t('&laquo; Back to your galleries'))
      )
    );
  }

  function Add_Gallery_Count_Notice($views){
    If (!$this->Check_Gallery_Count()): ?><div id="message" class="updated"><p><?php $this->Pro_Notice('do_you_like') ?></p></div><?php EndIf;
    return $views;
  }

  function Print_Dashboard_Styles(){
    If (!$this->Check_Gallery_Count()): ?>
    <style type="text/css">a[href*="post-new.php?post_type=fancy-gallery"]{ display: none !important }</style>
    <?php EndIf;
  }

  function Filter_Admin_Bar_Menu($admin_bar){
    If (!$this->Check_Gallery_Count()) $admin_bar->remove_node(SPrintF('new-%s', $this->gallery_post_type));
  }

} /* End of the Class */
New wp_plugin_fancy_gallery;
} /* End of the If-Class-Exists-Condition */