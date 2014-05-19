<table class="form-table">

<tr valign="top">
  <th scope="row" ><?php Echo $this->t('Lightbox') ?></th>
  <td>
    <p>
      <input type="radio" name="lightbox" value="none" id="lightbox_none" <?php Checked ($this->get_option('lightbox'), 'none') ?> >
      <label for="lightbox_none"><strong><?php Echo $this->t('No lightbox') ?></strong></label>
      <?php Echo $this->t('Choose this option if you want to use an external lightbox library.') ?>
    </p>

    <p>
      <input type="radio" name="lightbox" value="fancybox1" id="lightbox_fancybox1" <?php Checked ($this->get_option('lightbox'), 'fancybox1') ?> >
      <label for="lightbox_fancybox1"><strong><?php Echo $this->t('FancyBox version 1.x.') ?></strong></label>
      <?php Echo $this->t('FancyBox is a tool for displaying images in a Mac-style "lightbox" that floats overtop of web page. Licensed under the <a href="http://wordpress.org/about/gpl/" target="_blank">GPL License</a>.') ?>
    </p>

    <p>
      <input type="radio" name="lightbox" value="fancybox2" id="lightbox_fancybox2" <?php Checked ($this->get_option('lightbox'), 'fancybox2') ?> >
      <label for="lightbox_fancybox2"><strong><?php Echo $this->t('FancyBox version 2.x.') ?></strong></label>
      <?php Echo $this->t('Version 2 supports image thumbnails, slideshows and it is fully repsonsive. Unfortunately this version is licensed under the <a href="http://creativecommons.org/licenses/by-nc/3.0/" target="_blank">CC BY-NC 3.0 License</a>.') ?>
    </p>
    <ol>
      <li><?php Echo $this->t('FancyBox 2 is not GPL compatible and cannot be part of this plugin itself.') ?></li>
      <li><?php Echo $this->t('By activating this option the library will be included from an external hoster (<a href="http://cdnjs.com/libraries/fancybox/" target="_blank">CDNJS.com</a>).') ?></li>
      <li><?php Echo $this->t('By using FancyBox 2 you need to agree the lightbox authors <a href="http://fancyapps.com/fancybox/#license" target="_blank">license agreement</a>.') ?></li>
    </ol>

  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Loop mode') ?></th>
  <td>
    <input type="checkbox" name="loop" id="loop" value="yes" <?php Checked ($this->get_option('loop'), 'yes') ?> >
    <label for="loop"><?php Echo $this->t('Will enable the user to get from the last image to the first one with the "Next &raquo;" button.') ?></label>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="padding"><?php Echo $this->t('Border width') ?></label></th>
  <td>
    <input type="text" name="padding" id="padding" value="<?php Echo IntVal($this->get_option('padding')) ?>" size="4"><?php Echo $this->t('px', 'Abbr. Pixels') ?><br>
    <small><?php Echo $this->t('Width of the image frame border. (in pixels)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row">
    <?php Echo $this->t('Close button') ?>
  </th>
  <td>
    <input type="checkbox" <?php Disabled (True) ?> >
    <label><?php Echo $this->t('Hide the close button.') ?></label> <small class="pro-notice"><?php $this->Pro_Notice() ?></small><br>
    <small><?php Echo $this->t('Not available in FancyBox 1.x') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Auto play') ?></th>
  <td>
    <input type="checkbox" name="auto_play" id="auto_play" value="yes" <?php Checked ($this->get_option('auto_play'), 'yes') ?> >
    <label for="auto_play"><?php Echo $this->t('Start the slideshow when the user opens the first gallery item.') ?></label><br>
    <small><?php Echo $this->t('Not available in FancyBox 1.x') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="play_speed"><?php Echo $this->t('Play speed') ?></label></th>
  <td>
    <input type="text" name="play_speed" id="play_speed" value="<?php Echo IntVal($this->get_option('play_speed')) ?>" size="4"><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />
    <small><?php Echo $this->t('Speed of the slideshow. (in milliseconds)') ?> <?php Echo $this->t('Not available in FancyBox 1.x') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Image title') ?></th>
  <td>
    <input type="radio" name="use_as_image_title" value="title" id="use_as_image_title_title" <?php Checked ($this->get_option('use_as_image_title'), 'title') ?> >
    <label for="use_as_image_title_title"><?php Echo $this->t('Use the title of the image as title of the light box.') ?></label><br>

    <input type="radio" name="use_as_image_title" value="alt_text" id="use_as_image_title_alt_text" <?php Checked ($this->get_option('use_as_image_title'), 'alt_text') ?> >
    <label for="use_as_image_title_alt_text"><?php Echo $this->t('Use the alternative text of the image as title of the light box.') ?></label><br>

    <input type="radio" name="use_as_image_title" value="caption" id="use_as_image_title_caption" <?php Checked ($this->get_option('use_as_image_title'), 'caption') ?> >
    <label for="use_as_image_title_caption"><?php Echo $this->t('Use the image caption as title of the light box.') ?></label><br>

    <input type="radio" id="use_as_image_title_description" <?php Disabled (True) ?> >
    <label for="use_as_image_title_description"><?php Echo $this->t('Use the image description as title of the light box.') ?></label>
    <small class="pro-notice"><?php $this->Pro_Notice() ?></small><br />

    <input type="radio" id="use_as_image_title_none" <?php Disabled (True) ?> >
    <label for="use_as_image_title_none"><?php Echo $this->t('Do not show image titles.') ?></label>
    <small class="pro-notice"><?php $this->Pro_Notice() ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><?php Echo $this->t('Title position') ?></th>
  <td>
    <input type="radio" name="title_position" value="float" id="title_position_float" <?php Checked ($this->get_option('title_position'), 'float') ?> >
    <label for="title_position_float"><?php Echo $this->t('Outside the light box') ?> (<?php Echo $this->t('Does not work with multiline titles.') ?>)</label><br>

    <input type="radio" name="title_position" value="inside" id="title_position_inside" <?php Checked ($this->get_option('title_position'), 'inside') ?> >
    <label for="title_position_inside"><?php Echo $this->t('Inside the light box') ?> (<?php Echo $this->t('Does not work blameless with multiline titles.') ?>)</label><br>

    <input type="radio" name="title_position" value="over" id="title_position_over" <?php Checked ($this->get_option('title_position'), 'over') ?> >
    <label for="title_position_over"><?php Echo $this->t('Over the image') ?> (<?php Echo $this->t('Works fine with multiline titles.') ?>)</label><br>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="controls_position"><?php Echo $this->t('Controls position') ?></label></th>
  <td>
    <select name="controls_position" id="controls_position">
      <option value="top" <?php Selected ($this->get_option('controls_position'), 'top') ?> ><?php _e('Top') ?></option>
      <option value="bottom" <?php Selected ($this->get_option('controls_position'), 'bottom') ?> ><?php _e('Bottom') ?></option>
      <option value="none" <?php Selected ($this->get_option('controls_position'), 'none') ?> ><?php _e('None') ?></option>
    </select><br>
    <small><?php Echo $this->t('Position of the Prev, Next, Slideshow, Stop, etc. controls.') ?>
    <?php Echo $this->t('Not available in FancyBox 1.x') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="thumbs_position"><?php Echo $this->t('Thumbnails position') ?></label></th>
  <td>
    <select name="thumbs_position" id="thumbs_position">
      <option value="top" <?php Selected ($this->get_option('thumbs_position'), 'top') ?> ><?php _e('Top') ?></option>
      <option value="bottom" <?php Selected ($this->get_option('thumbs_position'), 'bottom') ?> ><?php _e('Bottom') ?></option>
      <option value="none" <?php Selected ($this->get_option('thumbs_position'), 'none') ?> ><?php _e('None') ?></option>
    </select><br>
    <small><?php Echo $this->t('Position of preview thumbnails relative to the lightbox.') ?> <?php Echo $this->t('Not available in FancyBox 1.x') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="open_effect"><?php Echo $this->t('Opening effect') ?></label></th>
  <td>
    <select name="open_effect" id="open_effect">
      <option value="fade" <?php Selected ($this->get_option('open_effect'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
      <option value="elastic" <?php Selected ($this->get_option('open_effect'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
      <option value="none" <?php Selected ($this->get_option('open_effect'), 'none') ?> ><?php Echo $this->t('No effect') ?></option>
    </select>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="open_speed"><?php Echo $this->t('Opening speed') ?></label></th>
  <td>
    <input type="text" name="open_speed" id="open_speed" value="<?php Echo IntVal($this->get_option('open_speed')) ?>" size="4"><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br />
    <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="close_effect"><?php Echo $this->t('Closing effect') ?></label></th>
  <td>
    <select name="close_effect" id="close_effect">
      <option value="fade" <?php Selected ($this->get_option('close_effect'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
      <option value="elastic" <?php Selected ($this->get_option('close_effect'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
      <option value="none" <?php Selected ($this->get_option('close_effect'), 'none') ?> ><?php Echo $this->t('No effect') ?></option>
    </select>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="close_speed"><?php Echo $this->t('Closing speed') ?></label></th>
  <td>
    <input type="text" name="close_speed" id="close_speed" value="<?php Echo IntVal($this->get_option('close_speed')) ?>" size="4"><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br>
    <small><?php Echo $this->t('Speed of the fade and elastic transitions. (in milliseconds)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="change_effect"><?php Echo $this->t('Changing effect') ?></label></th>
  <td>
    <select name="change_effect" id="change_effect">
      <option value="fade" <?php Selected ($this->get_option('change_effect'), 'fade') ?> ><?php Echo $this->t('Fade') ?></option>
      <option value="elastic" <?php Selected ($this->get_option('change_effect'), 'elastic') ?> ><?php Echo $this->t('Elastic') ?></option>
      <option value="none" <?php Selected ($this->get_option('change_effect'), 'none') ?> ><?php Echo $this->t('No effect') ?></option>
    </select>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="change_speed"><?php Echo $this->t('Image resizing speed') ?></label></th>
  <td>
    <input type="text" name="change_speed" id="change_speed" value="<?php Echo IntVal($this->get_option('change_speed')) ?>" size="4"><?php Echo $this->t('msec', 'Abbr. Milliseconds') ?><br>
    <small><?php Echo $this->t('Speed of resizing when changing gallery items. (in milliseconds)') ?></small>
  </td>
</tr>

<tr valign="top">
  <th scope="row"><label for="script_position"><?php Echo $this->t('Script position') ?></label></th>
  <td>
    <select name="script_position" id="script_position">
      <option value="footer" <?php Selected ($this->get_option('script_position'), 'footer') ?> ><?php Echo $this->t('Footer of the website') ?></option>
      <option value="header" <?php Selected ($this->get_option('script_position'), 'header') ?> ><?php Echo $this->t('Header of the website') ?></option>
    </select><br>
    <small><?php Echo $this->t('Please choose the position of the javascript. Footer is recommended. Use "Header" if you have trouble to make the Fancybox work.') ?></small>
  </td>
</tr>

</table>