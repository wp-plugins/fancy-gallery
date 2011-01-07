<table class="form-table">
<tr valign="top">
  <th scope="row"><?php Echo $this->t('Loop mode') ?></th>
  <td>
    <input type="checkbox" name="cyclic" value="yes" <?php Checked ($this->get_option('cyclic'), 'yes') ?>/>            
    <?php Echo $this->t('Will enable the user to get from the last image to the first one with the "Next &raquo;" button.') ?>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Scrollbars') ?></th>
  <td>
    <select name="scrolling">
      <option value=""><?php Echo $this->t('Automatic') ?></option>
      <option value="yes" <?php Selected ($this->get_option('scrolling'), 'yes') ?> ><?php _e('Yes') ?></option>
      <option value="no" <?php Selected ($this->get_option('scrolling'), 'no') ?> ><?php _e('No') ?></option>
    </select>
    (<?php Echo $this->t('"Automatic" means scrollbars will be visibly if necessary. "Yes" and "No" should be clear.') ?>)<br />
    <small><?php Echo $this->t('This option controls the appearance of the scrollbars inside the fancy box.') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Center on scroll') ?></th>
  <td>
    <input type="checkbox" name="center_on_scroll" value="yes" <?php Checked ($this->get_option('center_on_scroll'), 'yes') ?>/>            
    <?php Echo $this->t('Keep the FancyBox always in the center of the screen while scrolling the page.') ?>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Close button') ?></th>
  <td>
    <input type="checkbox" name="hide_close_button" value="yes" <?php Checked ($this->get_option('hide_close_button'), 'yes') ?>/>            
    <?php Echo $this->t('Hide the close button.') ?>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Overlay opacity') ?></th>
  <td>
    <input type="text" name="overlay_opacity" value="<?php Echo IntVal($this->get_option('overlay_opacity', 30)) ?>" size="3" />%<br />            
    <small><?php Echo $this->t('Percentaged opacity of the background of the FancyBox. Should be a value from 0 (invisible) to 100 (opaque). (Default is 30)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Overlay color') ?></th>
  <td>
    <input type="text" name="overlay_color" value="<?php Echo $this->get_option('overlay_color', '#666') ?>" class="color" /><br />            
    <div class="colorpicker"></div>
    <small><?php Echo $this->t('Please choose the color of the "darker" background.') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Border width') ?></th>
  <td>
    <input type="text" name="border_width" value="<?php Echo IntVal($this->get_option('border_width', 10)) ?>" size="4" /><?php Echo $this->t('px', 'Abbr. Pixels') ?><br />            
    <small><?php Echo $this->t('Width of the image frame border. (in pixels)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Image title') ?></th>
  <td>
    <input type="radio" name="use_as_image_title" value="title" <?php Checked ($this->get_option('use_as_image_title', 'title'), 'title') ?>/> <?php Echo $this->t('Use the title of the image as title of the fancy box.') ?><br />
    <input type="radio" name="use_as_image_title" value="alt_text" <?php Checked ($this->get_option('use_as_image_title'), 'alt_text') ?>/> <?php Echo $this->t('Use the alternative text of the image as title of the fancy box.') ?><br />
    <input type="radio" name="use_as_image_title" value="caption" <?php Checked ($this->get_option('use_as_image_title'), 'caption') ?>/> <?php Echo $this->t('Use the image caption as title of the fancy box.') ?><br />
    <input type="radio" name="use_as_image_title" value="description" <?php Checked ($this->get_option('use_as_image_title'), 'description') ?>/> <?php Echo $this->t('Use the image description as title of the fancy box.') ?><br />
    <input type="radio" name="use_as_image_title" value="none" <?php Checked ($this->get_option('use_as_image_title'), 'none') ?>/> <?php Echo $this->t('Do not show image titles.') ?>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Title position') ?></th>
  <td>
    <select name="title_position">
      <option value="float" <?php Selected ($this->get_option('title_position'), 'float') ?> ><?php Echo $this->t('Outside the FancyBox') ?> (<?php Echo $this->t('Does not work with multiline titles.') ?>)</option>
      <option value="inside" <?php Selected ($this->get_option('title_position'), 'inside') ?> ><?php Echo $this->t('Inside the FancyBox') ?> (<?php Echo $this->t('Does not work blameless with multiline titles.') ?>)</option>
      <option value="over" <?php Selected ($this->get_option('title_position'), 'over') ?> ><?php Echo $this->t('Over the image') ?> (<?php Echo $this->t('Works fine with multiline titles.') ?>)</option>
    </select>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Opening transition') ?></th>
  <td>
    <select name="transition_in">
      <option value="fade" <?php Selected ($this->get_option('transition_in'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
      <option value="elastic" <?php Selected ($this->get_option('transition_in'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
      <option value="none" <?php Selected ($this->get_option('transition_in'), 'none') ?> ><?php Echo $this->t('No transition') ?></option>
    </select>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Opening speed') ?></th>
  <td>
    <input type="text" name="speed_in" value="<?php Echo IntVal($this->get_option('speed_in', 300)) ?>" size="4" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
    <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Closing transition') ?></th>
  <td>
    <select name="transition_out">
      <option value="fade" <?php Selected ($this->get_option('transition_out'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
      <option value="elastic" <?php Selected ($this->get_option('transition_out'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
      <option value="none" <?php Selected ($this->get_option('transition_out'), 'none') ?> ><?php Echo $this->t('No transition') ?></option>
    </select>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Closing speed') ?></th>
  <td>
    <input type="text" name="speed_out" value="<?php Echo IntVal($this->get_option('speed_out', 300)) ?>" size="4" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
    <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Image resizing speed') ?></th>
  <td>
    <input type="text" name="change_speed" value="<?php Echo IntVal($this->get_option('change_speed', 300)) ?>" size="4" /><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />            
    <small><?php Echo $this->t('Speed of resizing when changing gallery items. (in milliseconds)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Single images') ?></th>
  <td>
    <input type="checkbox" name="associate_single_images" value="yes" <?php Checked ($this->get_option('associate_single_images'), 'yes') ?> />            
    <?php Echo $this->t('Consolidate single images in a post to one group so the user can navigate through them.') ?><br />            
    <small><?php Echo $this->t('So you will have an image navigation for all images.') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Appearance problems') ?></th>
  <td>
    <input type="checkbox" name="img_block_fix" value="yes" <?php Checked ($this->get_option('img_block_fix'), 'yes') ?> />            
    <?php Echo $this->t('Convert gallery images to inline elements. (Tick this box if your galleries have only one column.)') ?><br />            
    <small><?php Echo $this->t('Some themes let images appear as block elements. This effects that your image galleries have only one column.') ?></small>
  </td>
</tr>

</table>
