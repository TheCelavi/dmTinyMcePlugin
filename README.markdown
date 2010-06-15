dmTinyMcePlugin
===============

dmTinyMcePlugin is a Diem plugin that implements TinyMCE - Javascript WYSIWYG Editor.
This plugin is port of [dmCkEditorPlugin](http://diem-project.org/plugins/dmckeditorplugin "dmCkEditorPlugin") by Thibault Duplessis,
borrows some code from it and have very similar features.

dmTinyMcePlugin is still in development phase.

##TinyMCE on admin form
As you would do for a [markdown field](page:44#configuration-files:config-doctrine-schema-yml:markdown-field),
precise in the [schema.yml](page:44#configuration-files:config-doctrine-schema-yml)
that the field is managed by TinyMCE:

    Post:
      columns:
        body:    { type: clob, extra: tinymce }

##TinyMCE as a front widget
The plugin installs a new widget, available in front Add menu: HTML content.


##Configure TinyMCE
You can pass options to the javascript editor.
*config/dm/config.yml*
[code]  
all:

  tinymce:

    config:
      path: "/js/tiny_mce/tiny_mce.js"
      content_css: "/diem-ipsum/web/themeCoolWater/css/typography.css"
      theme_advanced_buttons1: "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,link,unlink,anchor,image,media,|,fullscreen,code,cleanup,styleprops"
      theme_advanced_buttons2: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,bullist,numlist,outdent,indent"
      theme_advanced_buttons3: ""
      
[/code]

To configure TinyMCE for the whole project, use *config/dm/config.yml*.
For the front application only, *apps/front/config/dm/config.yml*.
For the admin application only, *apps/admin/config/dm/config.yml*.

TinyMCE is not distributed with this plugin, you need to [download](http://tinymce.moxiecode.com/download.php "download") it and extract to *web/js/tiny_mce/* folder.

You can find out how to configure TinyMCE on [TinyMCE wiki](http://wiki.moxiecode.com/index.php/TinyMCE:Configuration "TinyMCE wiki")

Location of TinyMCE library can be configured in *config/dm/config.yml*:

[code]
all:

  tinymce:
  
    config:
      path: "/js/custom/folder/tiny_mce.js"
 
[/code]
