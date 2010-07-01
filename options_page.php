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
    <input type="text" name="overlay_color" value="<?php Echo $this->Load_Setting('overlay_color', '#666') ?>" class="color" /><br />            
    <div class="colorpicker"></div>
    <small><?php Echo $this->t('Please choose the color of the "darker" background.') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Border width') ?></th>
  <td>
    <input type="text" name="border_width" value="<?php Echo IntVal($this->Load_Setting('border_width', 10)) ?>" size="4" /><?php Echo $this->t('px', 'Abbr. Pixels') ?><br />            
    <small><?php Echo $this->t('Width of the image frame border. (in pixels)') ?></small>
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

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Appearance problems') ?></th>
  <td>
    <input type="checkbox" name="img_block_fix" value="yes" <?php Checked ($this->Load_Setting('img_block_fix'), 'yes') ?> />            
    <?php Echo $this->t('Convert gallery images to inline elements. (Tick this box if your galleries have only one column.)') ?><br />            
    <small><?php Echo $this->t('Some themes let images appear as block elements. This effects that your image galleries have only one column.') ?></small>
  </td>
</tr>

</table>
