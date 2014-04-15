<?php If ($this->Get_Option('gallery_management') == 'yes'): ?>

<p><?php Echo $this->t('Please select the taxonomies you need to classify your galleries.') ?></p>
<?php
$active_taxonomies = (Array) $this->Get_Option('gallery_taxonomies');
ForEach ($this->arr_taxonomies AS $taxonomy => $tax_args) : ?>
<p>
  <input type="checkbox" name="gallery_taxonomies[<?php Echo $taxonomy ?>][name]" id="gallery_taxonomies_<?php Echo $taxonomy ?>" value="<?php Echo $taxonomy ?>" <?php Checked(IsSet($active_taxonomies[$taxonomy])) ?> />
  <label for="gallery_taxonomies_<?php Echo $taxonomy ?>"><?php Echo $tax_args['labels']['name'] ?></label>
  (<input type="checkbox" name="gallery_taxonomies[<?php Echo $taxonomy ?>][hierarchical]" id="gallery_taxonomies_<?php Echo $taxonomy ?>_hierarchical" <?php Checked(IsSet($active_taxonomies[$taxonomy]['hierarchical'])) ?>>
  <label for="gallery_taxonomies_<?php Echo $taxonomy ?>_hierarchical"><?php Echo $this->t('hierarchical') ?></label>)
</p>
<?php EndForEach ?>
<?php ForEach (Array($this->t('Events'), $this->t('Places'), $this->t('Dates'), $this->t('Persons'), $this->t('Photographers')) AS $tax): ?>
<p>
  <input type="checkbox" disabled> <label><?php Echo $tax ?></label>
  (<input type="checkbox" disabled> <label><?php Echo $this->t('hierarchical') ?></label>)
</p>
<?php EndForEach ?>
<p class="pro-notice"><?php $this->Pro_Notice() ?></p>
<p class="pro-notice"><?php $this->Pro_Notice('custom_tax') ?></p>

<?php Else: ?>
<p><?php Echo $this->t('You need to activate the gallery management function before you can choose gallery taxonomies.') ?></p>
<?php EndIf;