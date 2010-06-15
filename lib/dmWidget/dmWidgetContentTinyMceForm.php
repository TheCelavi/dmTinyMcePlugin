<?php

class dmWidgetContentTinyMceForm extends dmWidgetPluginForm
{

  public function configure()
  {
    parent::configure();

    $this->widgetSchema['html'] = new sfWidgetFormTextareaDmTinyMce();
    
    $this->validatorSchema['html'] = new sfValidatorString();
  }

  protected function renderContent($attributes)
  {
    return $this->getHelper()->tag('ul.dm_form_elements',
      $this->getHelper()->tag('li.dm_form_element.clearfix', $this['html']->field()->error()).
      $this['cssClass']->renderRow()
    );
  }
}