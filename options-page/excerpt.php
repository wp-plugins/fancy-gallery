<p>
  <?php Echo $this->t('Usually posts have optional hand-crafted summaries of their content. But galleries do not.') ?>
  <?php Echo $this->t('Gallery excerpts are randomly chosen images from a gallery.') ?>
  <i><?php Echo $this->t('These settings may be overridden for individual galleries.') ?></i>
</p>

<p>
  <input type="checkbox" name="disable_excerpts" id="disable_excerpts" value="yes" <?php Checked($this->Get('disable_excerpts'), 'yes') ?> >
  <label for="disable_excerpts"><?php Echo $this->t('Do not generate excerpts out of random gallery images.') ?></label>
</p>

<table>
<tr>
  <td><label for=""><?php Echo $this->t('Images per excerpt') ?></label></td>
  <td>
    <input type="number" id="" value="<?php Echo Esc_Attr($this->Get('excerpt_image_number')) ?>" <?php Disabled(True) ?> >
    <span class="asterisk">*</span>
  </td>
  </tr>
</tr>
<tr>
  <td><label for=""><?php Echo $this->t('Thumbnail width') ?></label></td>
  <td>
    <input type="number" id="" value="<?php Echo Esc_Attr($this->Get('excerpt_thumb_width')) ?>" <?php Disabled(True) ?> > px
    <span class="asterisk">*</span>
  </td>
</tr>
<tr>
  <td><label for=""><?php Echo $this->t('Thumbnail height') ?></label></td>
  <td>
    <input type="number" id="" value="<?php Echo Esc_Attr($this->Get('excerpt_thumb_height')) ?>" <?php Disabled(True) ?> > px
    <span class="asterisk">*</span>
  </td>
</tr>
</table>

<p>
  <span class="asterisk">*</span>
  <?php Echo $this->core->mocking_bird->Pro_Notice('feature') ?>
</p>