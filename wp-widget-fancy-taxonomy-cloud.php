<?php If (!Class_Exists('wp_widget_fancy_taxonomy_cloud')){
Class wp_widget_fancy_taxonomy_cloud Extends WP_Widget {
  var $fancy_gallery;

  Function __construct(){
    // Get Fancy Gallery
    If (IsSet($GLOBALS['wp_plugin_fancy_gallery']) && Is_Object($GLOBALS['wp_plugin_fancy_gallery']))
      $this->fancy_gallery = $GLOBALS['wp_plugin_fancy_gallery'];
    Else
      return False;

    // Setup the Widget data
    parent::__construct (
      False,
      $this->t('Fancy Taxonomy Cloud'),
      Array('description' => $this->t('Displays your Gallery taxonomies as "Tag Cloud".'))
    );
  }

  Function t ($text, $context = ''){
    return $this->fancy_gallery->t($text, $context);
  }

  Function Default_Options(){
    // Default settings
    return Array(
      'show_count' => False,
      'number'     => 0,
      'orderby'    => 'name',
      'order'      => 'RAND',
      'exclude'    => False
    );
  }

  Function Load_Options($options){
    $options = (ARRAY) $options;

    // Delete empty values
    ForEach ($options AS $key => $value)
      If (!$value) Unset($options[$key]);

    // Load options
    $this->arr_option = Array_Merge ($this->Default_Options(), $options);
  }

  Function Get_Option($key, $default = False){
    If (IsSet($this->arr_option[$key]) && $this->arr_option[$key])
      return $this->arr_option[$key];
    Else
      return $default;
  }

  Function Set_Option($key, $value){
    $this->arr_option[$key] = $value;
  }

  Function Form ($settings){
    // Load options
    $this->load_options ($settings); Unset ($settings);
    ?>
    <p class="pro-notice"><?php $this->fancy_gallery->Pro_Notice('widget') ?></p>
    <p>
      <label for="<?php Echo $this->Get_Field_Id('title') ?>"><?php Echo $this->t('Title') ?></label>:
      <input type="text" id="<?php Echo $this->Get_Field_Id('title') ?>" name="<?php Echo $this->get_field_name('title')?>" value="<?php Echo HTMLSpecialChars($this->get_option('title')) ?>" /><br />
      <small><?php Echo $this->t('Leave blank to use the widget default title.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('taxonomy') ?>"><?php Echo $this->t('Taxonomy') ?></label>:
      <select id="<?php Echo $this->Get_Field_Id('taxonomy') ?>" name="<?php Echo $this->Get_Field_Name('taxonomy') ?>">
      <?php ForEach(Get_Object_Taxonomies($this->fancy_gallery->gallery_post_type) AS $taxonomy) : $taxonomy = Get_Taxonomy($taxonomy); ?>
      <option value="<?php Echo $taxonomy->name ?>" <?php Selected($this->get_option('taxonomy'), $taxonomy->name) ?>><?php Echo HTMLSpecialChars($taxonomy->labels->name) ?></option>
      <?php EndForEach ?>
      </select><br />
      <small><?php Echo $this->t('Please choose the Taxonomy the widget should display.') ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('number') ?>"><?php Echo $this->t('Number') ?></label>:
      <input type="text" id="<?php Echo $this->Get_Field_Id('number') ?>" name="<?php Echo $this->get_field_name('number')?>" value="<?php Echo HTMLSpecialChars($this->get_option('number')) ?>" size="4" /><br />
      <small><?php Echo $this->t('Leave blank (or "0") to show all.') ?></small>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude:' ); ?></label>
      <input type="text" value="<?php echo HTMLSpecialChars($this->get_option('exclude')); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id('exclude'); ?>" class="widefat" /><br />
      <small><?php Echo $this->t( 'Term IDs, separated by commas.' ); ?></small>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('orderby') ?>"><?php Echo $this->t('Order by') ?></label>:
      <select id="<?php Echo $this->Get_Field_Id('orderby') ?>" name="<?php Echo $this->Get_Field_Name('orderby') ?>">
      <option value="name" <?php Selected($this->get_option('orderby'), 'name') ?>><?php Echo __('Name') ?></option>
      <option value="count" <?php Selected($this->get_option('orderby'), 'count') ?>><?php Echo $this->t('Gallery Count') ?></option>
      </select>
    </p>

    <p>
      <label for="<?php Echo $this->Get_Field_Id('order') ?>"><?php Echo $this->t('Order') ?></label>:
      <select id="<?php Echo $this->Get_Field_Id('order') ?>" name="<?php Echo $this->Get_Field_Name('order') ?>">
      <option value="RAND" <?php Selected($this->get_option('order'), 'RAND') ?>><?php _e('Random') ?></option>
      <option value="ASC" <?php Selected($this->get_option('order'), 'ASC') ?>><?php _e('Ascending') ?></option>
      <option value="DESC" <?php Selected($this->get_option('order'), 'DESC') ?>><?php _e('Descending') ?></option>
      </select>
    </p>

    <?php
  }

  Function Widget ($args, $settings){ return False; }

  Function Update ($new_settings, $old_settings){
    return $new_settings;
  }

} /* End of Class */
Add_Action ('widgets_init', Create_Function ('','Register_Widget(\'wp_widget_fancy_taxonomy_cloud\');') );
} /* End of If-Class-Exists-Condition */
/* End of File */
