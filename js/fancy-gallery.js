(function($){
  var FG = FANCY_GALLERY; // Pointer to our global object

  // Render the template stylesheets
  if(typeof FG.templates == 'object'){
    var $head = $('head');
    for(index in FG.templates){
      $('<link rel="stylesheet">').attr('href', FG.templates[index]).appendTo($head);
    }
  }

  // group gallery items
  jQuery('div.fancy-gallery a')
  .each(function(){
    var $this = jQuery(this);
    $this.attr('rel', $this.parents('.fancy-gallery').attr('id'));
  });

  // Patch the linked images
  jQuery('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"], a[href$=".bmp"], a[href$=".wbmp"]').each(function(){

    // shorter access path
    var $lnk = jQuery(this);
    var $img = $lnk.find('img');

    if ($lnk.attr('rel') == '' || $lnk.attr('rel') == undefined)
      $lnk.attr('rel', 'single-image');

    if (FG.use_as_image_title == 'alt_text'){ // Copy the alternate texts
      $img.attr('title', $img.attr('alt'));
    }
    else if (FG.use_as_image_title == 'caption'){ // Copy the captions
      if (caption = $lnk.parent('.wp-caption').find('.wp-caption-text').html())
        $img.attr('title', caption);
    }

    // Convert images in inline-blocks
    if (FG.change_image_display == 'yes')
      $img.css('display', 'inline-block');

    // Copy the title tag from link to img
    if ($lnk.attr('title') == '' || $lnk.attr('title') == undefined)
      $lnk.attr('title', $img.attr('title'));

    // Add the fancybox class
    $lnk.addClass('fancybox');
  });

  // Add the light box effect to the linked images
  jQuery('a.fancybox')
  .unbind('click')
  .fancybox({
    // Fancybox 1.x
    cyclic:         (FG.loop == 'yes'),
    titlePosition:  FG.title_position,
    transitionIn:   FG.open_effect,
    speedIn:        parseInt(FG.open_speed),
    transitionOut:  FG.close_effect,
    speedOut:       parseInt(FG.close_speed),
    changeFade:     FG.change_effect,
    changeSpeed:    parseInt(FG.change_speed),

    // Fancybox 2.x
    loop:         (FG.loop == 'yes'),
    closeBtn:     (FG.hide_close_button != 'yes'),
    autoPlay:     (FG.auto_play == 'yes'),
    playSpeed:    parseInt(FG.play_speed),
    openEffect:   FG.open_effect,
    openSpeed:    parseInt(FG.open_speed),
    closeEffect:  FG.close_effect,
    closeSpeed:   parseInt(FG.close_speed),
    nextEffect:   FG.change_effect,
    nextSpeed:    parseInt(FG.change_speed),
    prevEffect:   FG.change_effect,
    prevSpeed:    parseInt(FG.change_speed),
    helpers: {
			title:      { type: FG.title_position },
			buttons:    { position: FG.controls_position },
      thumbs :    { width: 50, height: 50, position: FG.thumbs_position }
		},

    // All versions
    padding:      parseInt(FG.padding)
  });

})(jQuery);