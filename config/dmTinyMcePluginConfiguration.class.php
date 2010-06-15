<?php

class dmTinyMcePluginConfiguration extends sfPluginConfiguration
{
  
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $this->dispatcher->connect('dm.form_generator.widget_subclass', array($this, 'listenToFormGeneratorWidgetSubclassEvent'));

    $this->dispatcher->connect('dm.context.loaded', array($this, 'listenToContextLoadedEvent'));
  }

  public function listenToContextLoadedEvent(sfEvent $e)
  {
    
    if ($this->configuration instanceof dmAdminApplicationConfiguration)
    {
      $e->getSubject()->getResponse()
      ->addStylesheet('/dmTinyMcePlugin/css/admin.css');
    }
    
    $e->getSubject()->getResponse()
     ->addJavascript(sfConfig::get('dm_tinymce_path'))
     ->addJavascript('/dmTinyMcePlugin/js/launcher.js');
       
    /*
     * Add tiny_mce.js and launcher.js to javascript compressor black list
     */
    $e->getSubject()->get('javascript_compressor')->addToBlackList(array('tiny_mce.js', 'launcher.js'));
  }
  
  public function listenToFormGeneratorWidgetSubclassEvent(sfEvent $e, $subclass)
  {
    if($this->isTinyMceColumn($e['column']))
    {
      $subclass = 'TextareaDmTinyMce';
    }

    return $subclass;
  }

  protected function isTinyMceColumn(sfDoctrineColumn $column)
  {
    return false !== strpos(dmArray::get($column->getTable()->getColumnDefinition($column->getName()), 'extra', ''), 'tinymce');
  }

}