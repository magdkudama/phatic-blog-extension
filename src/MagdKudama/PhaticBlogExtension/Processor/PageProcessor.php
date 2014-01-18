<?php

namespace MagdKudama\PhaticBlogExtension\Processor;

use MagdKudama\Phatic\Processor;
use MagdKudama\PhaticBlogExtension\Event\BasePageEvent;
use MagdKudama\PhaticBlogExtension\Event\Events;
use MagdKudama\PhaticBlogExtension\Model\Page;
use MagdKudama\PhaticBlogExtension\Parser\BaseParser;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PageProcessor extends BaseProcessor implements Processor
{
    public function getCollection()
    {
        /** @var BaseParser $collection */
        $collection = $this->container->get('blog_pages_collection');
        return $collection->read();
    }

    public function dump($page)
    {
        $this->dumpPage($page);
    }

    public function getName()
    {
        return 'page';
    }

    protected function dumpPage(Page $page)
    {
        $event = new BasePageEvent($page);

        $content = $this->getView()->render(
            $page->getFile()->getRelativePathname(),
            [
                'content' => file_get_contents($page->getFile()->getRealpath())
            ]
        );

        $page->setPageContent($content);
        $this->getFileSystem()->mkdir($this->getConfig()->getResultsPath() . $page->getFile()->getRelativePath());

        $this->getDispatcher()->dispatch(Events::BEFORE_PAGE_CREATED, $event);
        $this->getFileSystem()->dumpFile($this->getConfig()->getResultsPath() . $page->getFile()->getRelativePath() . '/index.html', $page->getPageContent());
    }
}