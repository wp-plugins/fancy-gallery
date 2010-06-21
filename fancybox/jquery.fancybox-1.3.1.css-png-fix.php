<?php

// Send Header Mime type
Header ('Content-Type: text/css');


// Load WordPress
$wp_load = 'wp-load.php';
While (!Is_File ('wp-load.php')){
  If (Is_Dir('../')) ChDir('../');
  Else Die('Could not find WordPress.');
}
Include_Once 'wp-load.php';

// Is the class ready?
If (!Class_exists('wp_plugin_fancy_gallery')) Die ('Could not find the Fancy Gallery Plugin.');

// I use an anonymous function because we are in the global NameSpace.
$base_url = Create_Function(
  '', 'Echo HTMLSpecialChars(call_user_func(Array(\'wp_plugin_fancy_gallery\', \'get_base_url\')));'
);

?>

#fancybox-loading.fancybox-ie div	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_loading.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-close		{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_close.png', sizingMethod='scale'); }

.fancybox-ie #fancybox-title-over	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_title_over.png', sizingMethod='scale'); zoom: 1; }
.fancybox-ie #fancybox-title-left	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_title_left.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-title-main	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_title_main.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-title-right	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_title_right.png', sizingMethod='scale'); }

.fancybox-ie #fancybox-left-ico		{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_nav_left.png', sizingMethod='scale'); }
.fancybox-ie #fancybox-right-ico	{ background: transparent; filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_nav_right.png', sizingMethod='scale'); }

.fancybox-ie #fancy-bg-n	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_n.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-ne	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_ne.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-e	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_e.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-se	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_se.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-s	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_s.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-sw	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_sw.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-w	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_w.png', sizingMethod='scale'); }
.fancybox-ie #fancy-bg-nw	{ filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php $base_url() ?>/fancybox/fancy_shadow_nw.png', sizingMethod='scale'); }
