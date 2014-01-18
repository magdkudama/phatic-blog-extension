<?php

namespace MagdKudama\PhaticBlogExtension\Processor;

use MagdKudama\Phatic\Processor;
use MagdKudama\PhaticBlogExtension\Model\Page;
use MagdKudama\PhaticBlogExtension\Parser\BaseParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class HomepageProcessor extends BaseProcessor implements Processor
{
    public function getCollection()
    {
        /** @var BaseParser $collection */
        $collection = $this->container->get('blog_homepage_collection');
        return $collection->read();
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