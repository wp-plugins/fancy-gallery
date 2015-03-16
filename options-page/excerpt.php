<p>
  <?php Echo $this->t('Usually posts have optional hand-crafted summaries of their content. But galleries do not.') ?>
  <?php Echo $this->t('Gallery excerpts are randomly chosen images from a gallery.') ?>
  <i><?php Echo $this->t('These settings may be overridden for individual galleries.') ?></i>
</p>

<p>
  <input type="checkbox" name="disable_excerpts" id="disable_excerpts" value="1" <?php Checked($this->Get('disable_excerpts')) ?> >
  <label for="disable_excerpts"><?php Echo $this->t('Do not generate excerpts out of random gallery images.') ?></label>
</p>

<table>
<tr>
  <td><label><?php Echo $this->t('Images per excerpt') ?></label></td>
  <td>
    <input type="number" value="<?php Echo Esc_Attr($this->Get('excerpt_image_number')) ?>" readonly="readonly"><span class="asterisk">*</span>
  </td>
</tr>

<tr>
  <td><label><?php Echo $this->t('Thumbnail width') ?></label></td>
  <td>
    <input type="number" value="<?php Echo Esc_Attr($this->Get('excerpt_thumb_width')) ?>" readonly="readonly">px<span class="asterisk">*</span>
  </td>
</tr>

<tr>
  <td><label><?php Echo $this->t('Thumbnail height') ?></label></td>
  <td>
    <input type="number" value="<?php Echo Esc_Attr($this->Get('excerpt_thumb_height')) ?>" readonly="readonly">px<span class="asterisk">*</span>
  </td>
</tr>
</table>

<p>
  <span class="asterisk">*</span>
  <?php Echo $this->core->mocking_bird->Pro_Notice('setting') ?>
</p>