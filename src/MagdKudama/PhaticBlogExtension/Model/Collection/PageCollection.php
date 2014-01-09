<?php

namespace MagdKudama\PhaticBlogExtension\Model\Collection;

use Countable;
use IteratorAggregate;
use Doctrine\Common\Collections\ArrayCollection;
use MagdKudama\PhaticBlogExtension\Model\Page;

class PageCollection implements IteratorAggregate, Countable
{
    private $pages;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

    public function add(Page $page)
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
        }

        return $this;
    }

    public function getIterator()
    {
        return $this->pages->getIterator();
    }

    public function count()
    {
        return count($this->pages);
    }
}