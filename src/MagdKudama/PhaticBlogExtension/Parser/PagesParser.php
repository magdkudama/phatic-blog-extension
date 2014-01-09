<?php

namespace MagdKudama\PhaticBlogExtension\Parser;

use MagdKudama\PhaticBlogExtension\Model\Collection\PageCollection;
use MagdKudama\PhaticBlogExtension\Model\Page;

class PagesParser extends BaseParser
{
    public function read()
    {
        $pagesCollection = new PageCollection();

        foreach ($this->getSearchCriteria() as $pageData) {
            $page = new Page();

            $page->setFile($pageData);

            $pagesCollection->add($page);
        }

        return $pagesCollection;
    }

    protected function getSearchCriteria()
    {
        return $this->getFinder()->files()->in($this->getConfig()->getPagesPath())->name('*.html')->notPath("_layouts");
    }
}