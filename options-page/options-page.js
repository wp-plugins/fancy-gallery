(function($){

  // Add the line highlighting
  jQuery('div.capability-selection:odd').addClass('highlight1');
  jQuery('div.capability-selection:odd:odd').addClass('highlight2');

	// Add a confirmation message to the delete link
  jQuery('a.delete-link').click(function(){
    return confirm( $delete_confirm_message );
  });

})(jQuery);