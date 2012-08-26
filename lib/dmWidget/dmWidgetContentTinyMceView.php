<?php

class dmWidgetContentTinyMceView extends dmWidgetPluginView
{
  public function configure()
  {
    parent::configure();

    $this->addRequiredVar(array('html'));
  }

  protected function doRender()
  {
    if ($this->isCachable() && $cache = $this->getCache()) {
            return $cache;
    }
    $vars = $this->getViewVars();
    $tinyMce = new dmTinyMce($this->getHelper());
    $html = $tinyMce->render($vars['html']);
    if ($this->isCachable()) {
            $this->setCache($html);
    }
    return $html;
  }

  public function doRenderForIndex()
  {
    return strip_tags($this->compiledVars['html']);   
  }
}