<?php

namespace MagdKudama\PhaticBlogExtension\Model\Collection;

use IteratorAggregate;
use Countable;
use MagdKudama\PhaticBlogExtension\Model\Post;
use Doctrine\Common\Collections\ArrayCollection;

class PostCollection implements IteratorAggregate, Countable
{
    private $posts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    public function add(Post $post)
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
        }

        return $this;
    }

    public function getIterator()
    {
        return $this->posts->getIterator();
    }

    public function count()
    {
        return count($this->posts);
    }

    public function orderByDate()
    {
        $iterator = $this->posts->getIterator();

        $iterator->uasort(function (Post $first, Post $second) {
            if ($first === $second) {
                return 0;
            }

            return $first->getCreatedAt() > $second->getCreatedAt() ? -1 : 1;
        });

        $this->posts = new ArrayCollection();
        foreach ($iterator as $element) {
            $this->add($element);
        }

        return $this;
    }
}