/**
 @preserve CLEditor Youtube Plugin v1.0.0
 http://premiumsoftware.net/cleditor
 requires CLEditor v1.2.2 or later
 
 Copyright 2011, Michael de Hart
 Dual licensed under the MIT or GPL Version 2 licenses.
*/

(function($) {

  // Define the table button
  $.cleditor.buttons.youtube = {
    name: "youtube",
    image: "youtube.gif",
    title: "Insert Youtube video",
    command: "inserthtml",
    popupName: "youtube",
    popupClass: "cleditorPrompt",
    popupContent:
      "Link invoeren:<br /><input type=text value='http://' size=40 /><br /><input type=button value=Invoegen  />",
    buttonClick: youtubeButtonClick
  };

  // Add the button to the default controls
  $.cleditor.defaultOptions.controls = $.cleditor.defaultOptions.controls
    .replace("rule ", "rule youtube ");

  // Table button click event handler
  function youtubeButtonClick(e, data) {

    // Wire up the submit button click event handler
    $(data.popup).children(":button")
      .unbind("click")
      .bind("click", function(e) {

        // Get the editor
        var editor = data.editor;
        // Get url height and width
        var $text = $(data.popup).find(":text"),
          width = 560,
          height = 315;
          
        var partUrl = $text[0].value.match("[\\?&]v=([^&#]*)");

        // Build the html
        var html;
        /*
        html = '<object type="application/x-shockwave-flash"'+
            'style="width:' + width + 'px; height:' + height + 'px;" data="http://'+
            'www.youtube.com/v/' + youtubeUrl + '"><param name="wmode"'+
            'value="transparent" /><param name="movie" value="http://'+
            'www.youtube.com/v/' + youtubeUrl + '" /></object>'; */
        html = '<iframe class="youtube-player" type="text/html" width="'+width+'" height="'+height+'" src="http://www.youtube.com/embed/'+partUrl[1]+'?rel=0" frameborder="0" allowfullscreen></iframe>';
        

        // Insert the html
        if (html)
          editor.execCommand(data.command, html, null, data.button);

        // Reset the text, hide the popup and set focus
        $text[0].value = "http://";
        editor.hidePopups();
        editor.focus();

      });

    }

})(jQuery);