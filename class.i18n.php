<?php
Namespace WordPress\Plugin\Fancy_Gallery;

class I18n {

  /*
  public function __construct(){
    Add_Action('init', Array($this, 'Load_TextDomain'), 1);
  }
  */

  public static function Load_TextDomain(){
    $locale = Apply_Filters('plugin_locale', Get_Locale(), __CLASS__);
    Load_TextDomain (__CLASS__, DirName(__FILE__).'/language/' . $locale . '.mo');
  }

  public static function Get_Text_Domain(){
    return __CLASS__;
  }

  public static function t($text, $context = Null){
    # Translates the string $text with context $context
    If (Empty($context))
      return Translate ($text, __CLASS__);
    Else
      return Translate_With_GetText_Context ($text, $context, __CLASS__);
  }

}