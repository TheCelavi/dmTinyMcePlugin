<?php

class dmWidgetContentTinyMceForm extends dmWidgetPluginForm {

    public function configure() {
        parent::configure();
        $this->widgetSchema['html'] = new sfWidgetFormTextareaDmTinyMce();
        $this->validatorSchema['html'] = new sfValidatorString();
    }

    protected function renderContent($attributes) {
        $formRenderer = new dmDialogFormRenderer(array(
                    new dmDialogFormSection(
                            array(
                                array('name' => 'html', 'is_big' => true, 'label' => false)
                            ),
                            'Basic'
                    ),
                    new dmDialogFormSection(
                            array(
                                array('name' => 'behaviors', 'is_big' => true),
                                array('name' => 'cssClass', 'is_big' => true)
                            ),
                            'Advanced'
                    )
                        ), $this);
        return $formRenderer->render();
    }

    public function getStylesheets() {
        return array_merge(
                        parent::getStylesheets(), dmDialogFormRenderer::getStylesheets(), array()
        );
    }

    public function getJavaScripts() {
        return array_merge(
                        parent::getJavaScripts(), dmDialogFormRenderer::getJavascripts(), array()
        );
    }

}