<?php

/**
 * sfWidgetFormTextareaDmTinyMce represents a Tiny MCE widget.
 *
 * You must include the Tiny MCE JavaScript file by yourself.
 */
 
class sfWidgetFormTextareaDmTinyMce extends sfWidgetFormTextarea
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * width:  Width
   *  * height: Height
   *  * config: TinyMCE configuration array
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('width');
    $this->addOption('height');
    $this->addOption('config', array());
    
    $this->setOption('config', array_merge($this->getOption('config'), sfConfig::get('dm_tinymce_config'))); 
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $textarea = parent::render($name, $value, $attributes, $errors);
		
		$config = $this->getOption('config');
		
		if($this->getOption('width'))
		{
			$config['width'] = sprintf('width:"%spx",', $this->getOption('width'));
		}
		
		if($this->getOption('height'))
		{
			$config['height'] = sprintf('width:"%spx",', $this->getOption('height'));
		}

		if(isset($config['content_css']))
		{
			sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
			$config['content_css'] = public_path($config['content_css']);
		}
		
		$config['mode'] = "exact";
		$config['elements'] = $this->generateId($name);
		
    $data = array(
    	"tinymce_element" => $config['elements'],
    	"tinymce_config" => $config,
    );
    $json = "<div class=\"dm_tinymce_json none\">".json_encode($data)."</div>\n";

    return $textarea.$json;
  }
  
}
