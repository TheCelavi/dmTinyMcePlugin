<?php

require_once dirname(__FILE__) . '/vendor/simplehtmldom/simple_html_dom.php';

/**
 * 
 * Modified dmCkEditor by Robert Gründler <robert@dubture.com>
 *
 */
class dmTinyMce {

    protected $helper;

    public function __construct(dmHelper $helper) {
        $this->helper = $helper;
    }

    /**
     * Renders the editor contents
     * @param string $data
     */
    public function render($data) {
        $html = str_get_html($data);
        foreach ($html->find('img') as $image) {
            $image = $this->updateImage($image);
        }

        foreach ($html->find('a.link') as $page) {
            $page = $this->updatePage($page);
        }
        return $html;
    }

    /**
     * @param string $page link to the page to update
     * @return void
     */
    protected function updatePage($page) {

        $id = str_replace('dmPage-', '', $page->id);
        if (!$id || !is_numeric($id)) {
            return $this->updateFile($page);
        }

        $pageRecord = dmDb::table('DmPage')->findOneByIdWithI18n($id);

        if (!$pageRecord) {
            return $page;
        }

        $url = $this->helper->link($pageRecord)->getHref();

        if ($page->href != $url) {
            $page->href = $url;
        }
        return $page;
    }

    /**
     * 
     * @param string $image
     * @return void
     */
    protected function updateImage($image) {
        $id = str_replace('dmMedia-', '', $image->id);

        if (!$id || !is_numeric($id)) {
            return $image;
        }

        $mediaRecord = dmDb::table('dmMedia')->findOneByIdWithFolder($id);

        if (!$mediaRecord) {
            return $image;
        }
        $media = $this->helper->media($mediaRecord);
        if (isset($image->width))
            $media->width($image->width);
        if (isset($image->height))
            $media->width($image->height);
        $src = $media->getSrc();

        if ($image->src != $src) {
            $image->src = $src;
        }
        return $image;
    }
    
    /**
     *
     * @param type $file 
     * @return void
     */
    protected function updateFile($file) {
        $id = str_replace('dmFile-', '', $file->id);
        if (!$id || !is_numeric($id)) {
            return $file;
        }
        $mediaRecord = dmDb::table('dmMedia')->findOneByIdWithFolder($id);
        if (!$mediaRecord) {
            return $file;
        }
        $url = $this->helper->link($mediaRecord)->getHref();
        if ($file->href != $url) {
            $file->href = $url;
        }
        return $file;
    }

}