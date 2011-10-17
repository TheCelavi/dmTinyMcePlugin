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