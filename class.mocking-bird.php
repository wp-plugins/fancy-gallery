<?php
Namespace WordPress\Plugin\Fancy_Gallery;

class Mocking_Bird {
  private
    $core, # Pointer to the core object
    $post_type; # The post type we talk about

  public function __construct($core){
    $this->core = $core;
    $this->post_type = $this->core->gallery_post_type->name;
    Add_Action('admin_init', Array($this, 'User_Creates_New_Post'));
    Add_Action('untrash_post', Array($this, 'User_Untrashes_Post'));
    Add_Action('admin_footer', Array($this, 'Change_Create_Post_Link'));
    Add_Action('admin_bar_menu', Array($this, 'Filter_Admin_Bar_Menu'), 999);
  }

  private function t($text, $context = False){
    return $this->core->t($text, $context);
  }

  function Pro_Notice($message = 'feature', $output = True){
    $arr_message = Array(
      'upgrade' => $this->t('Upgrade to Pro'),
      'upgrade_url' => '%s',
      'feature' => $this->t('This feature is available in the <a href="%s" target="_blank">premium version</a>.'),
      'custom_tax' => $this->t('Do you need a special taxonomy for your website? No problem! Just <a href="%s" target="_blank">get in touch</a>.'),
      'widget' => $this->t('This widget is available in the <a href="%s" target="_blank">premium version</a>. For now there will be no output in the front end of your website.'),
      #'count_limit' => $this->t('In the <a href="%s" target="_blank">Premium Version of Fancy Gallery</a> you can take advantage of the gallery management without any limitations.'),
    );

    If (IsSet($arr_message[$message])){
      $message = SPrintF($arr_message[$message], $this->t('http://dennishoppe.de/en/wordpress-plugins/fancy-gallery', 'Link to the authors website'));
      If ($output) Echo $message;
      Else return $message;
    }
    Else
      return False;
  }

  function Count_Posts($limit = -1){
    Static $count;
    If ($count)
      return $count;
    Else
      return $count = Count(Get_Posts(Array('post_type' => $this->post_type, 'post_status' => 'any', 'numberposts' => $limit)));
  }

  function Check_Post_Count(){
    return $this->Count_Posts(3) < 3;
  }

  function User_Creates_New_Post(){
    If (BaseName($_SERVER['SCRIPT_NAME']) == 'post-new.php' && IsSet($_GET['post_type']) && $_GET['post_type'] == $this->post_type && !$this->Check_Post_Count())
      $this->Print_Post_Count_Limit();
  }

  function User_Untrashes_Post($post_id){
    If (Get_Post_Type($post_id) == $this->post_type && !$this->Check_Post_Count()) $this->Print_Post_Count_Limit();
  }


  function Print_Post_Count_Limit(){
    WP_Die(
      SPrintF('<p>%s</p><p>%s</p>',
        $this->Pro_Notice('count_limit', False),
        SPrintF('<a href="%s" class="button">%s</a>', Admin_URL('edit.php?post_type=' . $this->post_type), $this->t('&laquo; Back to your galleries'))
      )
    );
  }

  function Change_Create_Post_Link(){
    If (!$this->Check_Post_Count()): ?>
    <script type="text/javascript">
    (function($){
      $('a[href*="post-new.php?post_type=<?php Echo $this->post_type ?>"]')
        .text('<?php $this->Pro_Notice('upgrade') ?>')
        .attr({
          'title': '<?php $this->Pro_Notice('upgrade') ?>',
          'href': '<?php $this->Pro_Notice('upgrade_url') ?>',
          'target': '_blank'
        })
        .css({
          'color': 'green',
          'font-weight': 'bold'
        });
    })(jQuery);
    </script>
    <?php EndIf;
  }

  function Filter_Admin_Bar_Menu($admin_bar){
    If (!$this->Check_Post_Count()) $admin_bar->Remove_Node(SPrintF('new-%s', $this->post_type));
  }

}