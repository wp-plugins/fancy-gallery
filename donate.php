<?php

If (!Class_Exists('wp_plugin_donation_to_dennis_hoppe')){
Class wp_plugin_donation_to_dennis_hoppe {
  var $text_domain;
  
  Function __construct(){
    // If we are not in admin panel. We bail out.
    If (!Is_Admin()) return False;
    
    // Load Text Domain
    $this->Load_TextDomain();
    
    // Register the Misc Setting if a user donated
    Add_Action('admin_menu', Array($this, 'prepare_settings_field'));
    
    // Check if the user has already donated
    If (get_option('donated_to_dennis_hoppe') == 'yes') return False;
    
    // Register the Dashboard Widget
    Add_Action('wp_dashboard_setup', Array($this, 'register_widget'));

    // Register donation message
    Add_Action('donation_message', Array($this, 'print_message'));
  }

  Function Load_TextDomain(){
    $this->text_domain = get_class($this);
    $lang_file = DirName(__FILE__) . '/donate_' . get_locale() . '.mo';
    If (Is_File ($lang_file)) load_textdomain ($this->text_domain, $lang_file);
  }
  
  Function t ($text, $context = ''){
    // Translates the string $text with context $context
    If ($context == '')
      return __($text, $this->text_domain);
    Else
      return _x($text, $context, $this->text_domain);
  }  
  
  Function register_widget(){    
    // Setup the Dashboard Widget
    wp_add_dashboard_widget(
      'donation-to-dennis-hoppe-' . Time(),
      $this->t('Please think about a donation!'),
      Array($this, 'print_message')
    );
  }
  
  Function print_message(){
    // Read current user
    Global $current_user; get_currentuserinfo();
    
    // Read the active plugins
    $arr_plugin = Array();
    ForEach (get_option('active_plugins') AS $plugin){
      $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
      If ( StrPos(StrToLower($plugin_data['Author']), 'dennis hoppe') !== False )
        $arr_plugin[] = $plugin_data['Title'];
    }

    // Write the Dashboard message
    ?><img src="http://www.gravatar.com/avatar/d50a70b7a2e91bb31e8f00c13149f059?s=100" class="alignright" alt="Dennis Hoppe" height="100" width="100" style="margin:0 0 3px 10px;" />
    
    <div style="text-align:justify">    
      <h4><?php PrintF ( $this->t('Hello %1$s!'), $current_user->display_name ) ?></h4>
      
      <p>
        <?php Echo $this->t('My name is Dennis Hoppe and I am a computer science student working and living in Berlin, Germany.') ?>
        <?php PrintF ($this->t('Beside other plugins and themes I developed %1$s.'), $this->Extended_Implode ($arr_plugin, ', ', ' ' . $this->t('and') . ' ')) ?>
        <?php Echo $this->t('I love the spirit of the open source movement, to write and share code and knowledge, but I think the system can work only if everyone contributes one\'s part properly.') ?>      
      </p>
      
      <p>
        <?php PrintF ($this->t('Because you are using %1$s of my plugins I hope you will appreciate my job.'), $this->Number_to_Word(Count($arr_plugin))) ?>
        <?php Echo $this->t('So please think about a donation. You would also help to keep alive and growing the community.') ?>
      </p>

    </div>
      
    <ul>
      <li>&raquo; <a href="http://www.amazon.de/wishlist/2AG0R8BHEOJOL" target="_blank"><?php Echo $this->t('Make a gift of the Amazon Wishlist.') ?></a></li>
      <li>&raquo; <?php Echo $this->t('Make a donation via PayPal:') ?>
                  <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=1220480" target="_blank"><?php Echo $this->t('U$ Dollars') ?></a> |
                  <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HECSPGLPTQL24" target="_blank"><?php Echo $this->t('&euro;uro') ?></a>
      </li>
    </ul>
    
    <div class="clear"></div><?php
  }
  
  Function prepare_settings_field (){
    // Register the option field
    register_setting( 'misc', 'donated_to_dennis_hoppe' );
    
    // Add Settings Field
    add_settings_section(
      get_class($this),
      $this->t('Donation to Dennis Hoppe'),
      Array($this, 'add_settings_field'),
      'misc'
    );
  }

  Function add_settings_field(){
    ?>
    <table class="form-table">
    <tr>
      <th scope="row" colspan="2" class="th-full">
        <input type="checkbox" name="donated_to_dennis_hoppe" value="yes" <?php checked(get_option('donated_to_dennis_hoppe'), 'yes') ?>/>
        <?php Echo $this->t('I give the affidavit that I have sent a donation to Dennis Hoppe or paid him a fee for his job.'); ?>
      </th>
    </tr>
    </table>
    <?php
  }
  
  Function Extended_Implode($array, $separator = ', ', $last_separator = ' and ' ){
    $array = (array) $array;
    If (Count($array) == 0) return '';
    If (Count($array) == 1) return $array[0];
    $last_item = Array_pop ($array);
    $result = Implode ($array, $separator) . $last_separator . $last_item;
    return $result;
  }
  
  Function Number_to_Word ($number){
    $arr_word = Array(
      0 => '',
      1 => $this->t('one'),
      2 => $this->t('two'),
      3 => $this->t('three'),
      4 => $this->t('four'),
      5 => $this->t('five'),
      6 => $this->t('six'),
      7 => $this->t('seven'),
      8 => $this->t('eight'),
      9 => $this->t('nine'),
      
      10 => $this->t('ten'),
      11 => $this->t('eleven'),
      12 => $this->t('twelve'),
      13 => $this->t('thirteen'),
      14 => $this->t('fouteen'),
      15 => $this->t('fifteen'),
      16 => $this->t('sixteen'),
      17 => $this->t('seventeen'),
      18 => $this->t('eighteen'),
      19 => $this->t('nineteen'),
      
      20 => $this->t('twenty'),      
      21 => $this->t('twenty-one'),
      22 => $this->t('twenty-two'),
      23 => $this->t('twenty-three'),
      24 => $this->t('twenty-four'),
      25 => $this->t('twenty-five'),
      26 => $this->t('twenty-six'),
      27 => $this->t('twenty-seven'),
      28 => $this->t('twenty-eight'),
      29 => $this->t('twenty-nine'),
      
      30 => $this->t('thirty'),
      31 => $this->t('thirty-one'),
      32 => $this->t('thirty-two'),
      33 => $this->t('thirty-three'),
      34 => $this->t('thirty-four'),
      35 => $this->t('thirty-five'),
      36 => $this->t('thirty-six'),
      37 => $this->t('thirty-seven'),
      38 => $this->t('thirty-eight'),
      39 => $this->t('thirty-nine'),
      
      40 => $this->t('fourty'),
      41 => $this->t('fourty-one'),
      42 => $this->t('fourty-two'),
      43 => $this->t('fourty-three'),
      44 => $this->t('fourty-four'),
      45 => $this->t('fourty-five'),
      46 => $this->t('fourty-six'),
      47 => $this->t('fourty-seven'),
      48 => $this->t('fourty-eight'),
      49 => $this->t('fourty-nine'),
      
      50 => $this->t('fifty'),
      
      60 => $this->t('sixty'),
      
      70 => $this->t('seventy'),
      
      80 => $this->t('eighty'),
      
      90 => $this->t('ninty'),
      
      100 => $this->t('hundred')
    );
    
    If (IsSet($arr_word[$number]))
      return $arr_word[$number];
    Else
      return False;
  }
  

} /* End of the Class */
New wp_plugin_donation_to_dennis_hoppe();
} /* End of the If-Class-Exists-Condition */
/* End of File */