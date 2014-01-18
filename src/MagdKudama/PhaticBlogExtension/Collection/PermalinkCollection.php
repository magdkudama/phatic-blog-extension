<?php

namespace MagdKudama\PhaticBlogExtension\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use IteratorAggregate;
use Countable;
use MagdKudama\PhaticBlogExtension\Permalink\PermalinkExtension;

class PermalinkCollection implements IteratorAggregate, Countable
{
    /** @var ArrayCollection */
    private $permalinks;

    public function __construct()
    {
        $this->permalinks = new ArrayCollection();
    }

    public function add(PermalinkExtension $permalink)
    {
        if (!$this->permalinks->contains($permalink)) {
            $this->permalinks->add($permalink);
        }

        return $this;
    }

    public function getIterator()
    {
        return $this->permalinks->getIterator();
    }

    public function findByName($name)
    {
        /** @var PermalinkExtension $permalink */
        foreach ($this->permalinks as $permalink) {
            if ($permalink->getName() === $name) {
                return $permalink;
            }
        }

        return false;
    }

    public function count()
    {
        return count($this->permalinks);
    }
}