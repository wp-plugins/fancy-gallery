<?php
Namespace WordPress\Plugin\Fancy_Gallery;

class I18n {

  public function __construct(){
    Add_Action('widgets_init', Array($this, 'Load_TextDomain'));
  }

  public function Load_TextDomain(){
    $locale = Apply_Filters('plugin_locale', Get_Locale(), __CLASS__);
    Load_TextDomain (__CLASS__, DirName(__FILE__).'/language/' . $locale . '.mo');
  }

  public function t ($text, $context = ''){
    # Translates the string $text with context $context
    If ($context == '')
      return Translate ($text, __CLASS__);
    Else
      return Translate_With_GetText_Context ($text, $context, __CLASS__);
  }

}