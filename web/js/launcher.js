$(document).ready(function() {
    if($("#dm_admin_content").length) {
        dmTinyMceInit(null, false);
    };
});

$.fn.dmWidgetContentTinyMceForm = function(widget) {
    this.parent().parent().css('width', '510px');
    dmTinyMceInit(widget, true);
};


function dmTinyMceInit(subject, is_widget) {

    jQuery.each(jQuery.find('div.dm_tinymce_json'), function(index, json_element) {
        var data = jQuery.parseJSON(jQuery(json_element).html());
        if(typeof tinyMCE == "undefined") {
            window.tinyMCEPreInit = {
                base: data.tinymce_base, 
                suffix : '', 
                query : ''
            };
            $.ajax({
                type: "GET",
                url: data.tinymce_path,
                dataType: "script",
                cache: true,
                async: false,
                success: function(){
                    tinymce.dom.Event.domLoaded = true;
                    dmTinyMceInitEditor(data);
                }
            });

        } else {
            dmTinyMceInitEditor(data);
        };
        jQuery(json_element).remove();
        if(is_widget) {
            dmTinyMceWidgetFormMonitor(data);
        };
    });
};

function dmTinyMceInitEditor(data) {
    var editor_parent_id = data.tinymce_element+'_parent';
    data.tinymce_config.oninit = function() {
        dmTinyMceInitMedia($('#'+editor_parent_id));
    };
    tinyMCE.init(data.tinymce_config);
};


function dmTinyMceWidgetFormMonitor(data) {
    
    var $tinyMce = $('#'+data.tinymce_element);
    $tinyMce.closest("form").submit(function() {

        // Fix for issue #1
        var input = $(this).find("textarea");
        var editor = tinyMCE.get(input.prop('id'));
        input.val(editor.getContent());

        return true;
    });
};

function dmTinyMceInitMedia(element) {

    var $dialog = element.closest('.ui-dialog-content');
    var namespace = $dialog.find('.dm_widget_content_tiny_mce_form').metadata().form_name;
    $dialog.bind('dialogclose', function(){
        $('#dm_page_tree').undelegate('a.ui-draggable', '.dmTinyMceDragStart' + namespace);
        $('#dm_media_browser').undelegate('li.image.ui-draggable', '.dmTinyMceDragStart' + namespace);
        $('#dm_media_browser').undelegate('li.application.ui-draggable, li.video.ui-draggable, li.audio.ui-draggable', '.dmTinyMceDragStart' + namespace)
    });

    var overlay_id = '';    

    $('#dm_page_tree').delegate('a.ui-draggable', 'dragstart.dmTinyMceDragStart' + namespace , function(event, ui){
        overlay_id = dmTinyMceCreateOverlay(element, $.dm.ctrl.getHref('+/dmTinyMce/page/id/'));
        $(this).on('dragstop.dmTinyMceDragStop'  + namespace, function(){
            $('#' + overlay_id).remove();
            $(this).off('.dmTinyMceDragStop' + namespace);
        });
    });
    
    $('#dm_media_browser').delegate('li.image.ui-draggable', 'dragstart.dmTinyMceDragStart' + namespace, function(event, ui) {
        overlay_id = dmTinyMceCreateOverlay(element, $.dm.ctrl.getHref('+/dmTinyMce/media/id/'));
        $(this).on('dragstop.dmTinyMceDragStop' + namespace, function(){
            $('#' + overlay_id).remove();
            $(this).off('.dmTinyMceDragStop' + namespace);
        });
    });
    
    $('#dm_media_browser').delegate('li.application.ui-draggable, li.video.ui-draggable, li.audio.ui-draggable', 'dragstart.dmTinyMceDragStart' + namespace, function(event, ui) {
        overlay_id = dmTinyMceCreateOverlay(element, $.dm.ctrl.getHref('+/dmTinyMce/file/id/'));
        $(this).on('dragstop.dmTinyMceDragStop' + namespace, function(){
            $('#' + overlay_id).remove();
            $(this).off('.dmTinyMceDragStop' + namespace);
        });
    });
    
};

function dmTinyMceCreateOverlay(element, link) {
 
    var overlay_id = 'drag_box_' + element.prop('id');
    $(document.body).append('<div id="'+overlay_id+'"><input style="width: 100%; height: 100%;" /></div>');

    var $box = element.find('.mceIframeContainer');
    var offset = $box.offset();

    $('#' + overlay_id).css({
        position: 'absolute',
        left: offset.left + 'px',
        top: offset.top + 'px',
        height: $box.innerHeight()-1 + 'px',
        width: $box.innerWidth()-3 + 'px',
        backgroundColor: 'white',
        opacity: 0.5,
        zIndex: 280000000,
        display: 'block'
    }).find('input:first').dmDroppableInput(function() {
        $.ajax({
            url: link + $(this).val().split(' ')[0].split(':')['1'],
            success: function(src) {
                var editor = tinyMCE.get(element.prop('id').substr(0, element.prop('id').length-7));
                editor.execCommand('mceInsertContent', false, src)
            }
        });
    });
    return overlay_id;
}
