<p>
  <?php Echo $this->t('Enable Gallery Management:') ?>
  <select name="gallery_management" id="gallery_management">
    <option value="yes" <?php Selected ($this->Get_Option('gallery_management'), 'yes') ?> ><?php _e('Yes') ?></option>
    <option value="no" <?php Selected ($this->get_option('gallery_management'), 'no') ?> ><?php _e('No') ?></option>
  </select>
</p>

<p>
  <?php Echo $this->t('The gallery management function enables you to manage your galleries separated from posts and pages.') ?>
  <?php Echo $this->t('After activating this feature you can find a new "Galleries" menu item on the left side of your dashboard.') ?>
</p>