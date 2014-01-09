<?php

namespace MagdKudama\PhaticBlogExtension\Processor;

use MagdKudama\Phatic\AbstractProcessor;
use MagdKudama\PhaticBlogExtension\Model\Page;
use MagdKudama\PhaticBlogExtension\Parser\HomepageParser;

class HomepageProcessor extends AbstractProcessor
{
    private $pages;

    public function setCollection(HomepageParser $pages)
    {
        $this->pages = $pages->read();
    }

    public function getCollection()
    {
        return $this->pages;
    }

    public function dump($page)
    {
        $this->dumpPage($page);
    }

    public function getName()
    {
        return 'homepage';
    }

    protected function dumpPage(Page $page)
    {
        $content = $this->getView()->render(
            '_layouts/homepage.html', []
        );

        $page->setPageContent($content);
        $this->getFileSystem()->dumpFile($this->getConfig()->getResultsPath() . 'index.html', $page->getPageContent());
    }
}