<?php

namespace MagdKudama\PhaticBlogExtension\Parser;

use MagdKudama\PhaticBlogExtension\Exception\PageNotFoundException;
use MagdKudama\PhaticBlogExtension\Model\Collection\PageCollection;
use MagdKudama\PhaticBlogExtension\Model\Page;

class HomepageParser extends BaseParser
{
    public function read()
    {
        $pagesCollection = new PageCollection();

        $criteria = $this->getSearchCriteria();
        if (count($criteria) == 0) {
            throw new PageNotFoundException("_pages/_layouts/homepage.html is needed");
        }

        foreach ($criteria as $pageData) {
            $page = new Page();

            $page->setFile($pageData);

            $pagesCollection->add($page);
        }

        return $pagesCollection;
    }

    protected function getSearchCriteria()
    {
        return $this->getFinder()->files()->in($this->getConfig()->getLayoutsPath())->name('homepage.html');
    }
}