<script type="text/javascript">
var $delete_confirm_message = "<?php Echo $this->t('Are you sure you want to delete this template?') ?>";
</script>

<ol>
  <?php ForEach ( $this->core->Get_Template_Files() AS $template_name => $properties ) : ?>
  <li>
    <?php If (Empty($properties['name'])) : ?>
      <em><?php Echo $properties['file'] ?></em>
    <?php Else : ?>
      <strong><?php Echo $properties['name'] ?></strong>
    <?php EndIf ?>
    <?php If ($properties['version']) : ?> <small>(<?php Echo $properties['version'] ?>)</small><?php Endif; ?>
    <?php If ($properties['author'] && !$properties['author_uri'] ) : ?>
      <?php Echo $this->t('by') ?> <?php Echo $properties['author'] ?>
    <?php ElseIf ($properties['author'] && $properties['author_uri'] ) : ?>
      <?php Echo $this->t('by') ?> <a href="<?php Echo $properties['author_uri'] ?>" target="_blank"><?php Echo $properties['author'] ?></a>
    <?php Endif ?>
    (<small><a href="<?php Echo $this->Get_Options_Page_Url(Array('delete' => $properties['file'])) ?>" title="<?php Echo $this->t('Delete this template') ?>" class="delete-link"><?php Echo $this->t('Delete') ?></a></small>)
    <?php If ($properties['description']) : ?><br><?php Echo $properties['description']; Endif ?><br>
    <small><?php PrintF($this->t('Found in <em>%s</em>.'), SubStr($properties['file'], StrLen(ABSPATH))) ?></small>
  </li>
  <?php EndForEach ?>
</ol>


<h4><?php Echo $this->t('Install a template in ZIP format') ?><?php $this->core->mocking_bird->Pro_Notice('unlock') ?></h4>
<p><?php Echo $this->t('If you have a template as a .zip archive, you may install it by uploading it here.') ?></p>
<p><label for="template_zip"><?php Echo $this->t('Template as ZIP File') ?></label> <input type="file" id="template_zip" <?php Disabled(True) ?> ></p>

<h4><?php Echo $this->t('Install a template in PHP format') ?><?php $this->core->mocking_bird->Pro_Notice('unlock') ?></h4>
<p><?php Echo $this->t('If you have a template as a .php file, you may install it by uploading it here.') ?></p>
<p><label for="template_php"><?php Echo $this->t('Templates PHP File') ?></label> <input type="file" id="template_php" <?php Disabled(True) ?> ></p>

<p><input type="button" value="<?php Echo $this->t('Install template and save all options') ?>" class="button-primary" <?php Disabled(True) ?> ></p>