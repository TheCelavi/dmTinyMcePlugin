(function($)
{
  ////front
  //$('#dm_page div.dm_widget').bind('dmWidgetLaunch', function()
  //{
  //
  //});

  // onload
  $(function()
  {
    dmTinyMceInit();
  });
  
})(jQuery);



 
 
$.fn.dmWidgetContentTinyMceForm = function(widget)
{
	this.parent().parent().css('width', '510px');
	dmTinyMceInit(widget);
};

 
function dmTinyMceInit(subject)
{

	jQuery.each(jQuery.find('div.dm_tinymce_json'), function(index, json_element) { 

	  data = jQuery.parseJSON(jQuery(json_element).html());
	  jQuery(json_element).remove();
		
		var editor_parent_id = data.tinymce_element+'_parent';
		
	  data.tinymce_config.oninit = function()
	  {

	  	dmTinyMceInitMedia($('#'+editor_parent_id));
	  }
	  
	  tinyMCE.init(data.tinymce_config);
	  
	});

};


 
function dmTinyMceInitMedia(element)
{

	var overlay_id = '';

  $('#dm_page_tree a.ui-draggable').live('dragstart', function(event, ui){
    overlay_id = dmTinyMceCreateOverlay(element, $.dm.ctrl.getHref('+/dmCkEditor/page/id/'));
  });

  $('#dm_page_tree a.ui-draggable').live('dragstop', function(event, ui) {
    $('#' + overlay_id).remove();
  });
  
  $('#dm_media_browser li.image.ui-draggable').live('dragstart', function(event, ui) {
    overlay_id = dmTinyMceCreateOverlay(element, $.dm.ctrl.getHref('+/dmCkEditor/media/id/'));
  });

  $('#dm_media_browser li.image.ui-draggable').live('dragstop', function(event, ui) {
    $('#' + overlay_id).remove();
  });

};



function dmTinyMceCreateOverlay(element, link)
{
  var overlay_id = 'drag_box_' + element.attr('id');

  var offset = element.offset();
  $(document.body).append('<div id="'+overlay_id+'"><input style="width: 100%; height: 100%;" /></div>');

  $('#' + overlay_id).css({
    position: 'absolute',
    left: offset.left + 'px',
    top: offset.top + 'px',
    height: element.height() + 'px',
    width: element.width() + 'px',
    backgroundColor: 'white',
    opacity: 0.5,
    zIndex: 1000,
    display: 'block'
  }).find('input:first').dmDroppableInput(function() {
    $.ajax({
      url: link + $(this).val().split(' ')[0].split(':')['1'],
      success: function(src) {
        
        //var editor.setData(src + editor.getData());
        var editor = tinyMCE.get(element.attr('id').substr(0, element.attr('id').length-7));
        editor.execCommand('mceInsertContent', false, src)
        //--alert( src + " |>>>| " + editor );
      }
    });
  });
  return overlay_id;
}
