<p>
  <?php Echo $this->t('These settings affect the appearance of the thumbnail images of this gallery.') ?>
</p>

<p>
  <label for="<?php Echo $this->Field_Name('thumb_width') ?>"><?php Echo $this->t('Thumbnail width:') ?></label>
  <input type="number" name="<?php Echo $this->Field_Name('thumb_width') ?>" id="<?php Echo $this->Field_Name('thumb_width') ?>" value="<?php Echo Esc_Attr($this->Get_Meta('thumb_width')) ?>" <?php Disabled(True) ?> > px <span class="asterisk">*</span>
</p>

<p>
  <label for="<?php Echo $this->Field_Name('thumb_height') ?>"><?php Echo $this->t('Thumbnail height:') ?></label>
  <input type="number" name="<?php Echo $this->Field_Name('thumb_height') ?>" id="<?php Echo $this->Field_Name('thumb_height') ?>" value="<?php Echo Esc_Attr($this->Get_Meta('thumb_height')) ?>" <?php Disabled(True) ?> > px <span class="asterisk">*</span>
</p>

<p>
  <input type="checkbox" name="<?php Echo $this->Field_Name('thumb_grayscale') ?>" id="<?php Echo $this->Field_Name('thumb_grayscale') ?>" value="yes" <?php Checked($this->Get_Meta('thumb_grayscale'), 'yes'); Disabled(True) ?> >
  <label for="<?php Echo $this->Field_Name('thumb_grayscale') ?>"><?php Echo $this->t('Convert thumbnails to grayscale.') ?> <span class="asterisk">*</span></label>
</p>

<p>
  <input type="checkbox" name="<?php Echo $this->Field_Name('thumb_negate') ?>" id="<?php Echo $this->Field_Name('thumb_negate') ?>" value="yes" <?php Checked($this->Get_Meta('thumb_negate'), 'yes'); Disabled(True) ?> >
  <label for="<?php Echo $this->Field_Name('thumb_negate') ?>"><?php Echo $this->t('Negate the thumbnails.') ?> <span class="asterisk">*</span></label>
</p>

<p>
  <span class="asterisk">*</span>
  <?php Echo $this->core->mocking_bird->Pro_Notice('feature') ?>
</p>