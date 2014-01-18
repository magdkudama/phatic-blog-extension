<?php

namespace MagdKudama\PhaticBlogExtension\Permalink;

use MagdKudama\PhaticBlogExtension\Collection\PermalinkCollection;
use MagdKudama\PhaticBlogExtension\Model\Post;

class PermalinkGuesser
{
    protected $collection;
    protected $permalinkOptions;

    public function __construct(PermalinkCollection $collection, array $permalinkOptions)
    {
        $this->collection = $collection;
        $this->permalinkOptions = $permalinkOptions;
    }

    public function getPermalink()
    {
        return $this->collection->findByName($this->permalinkOptions['type']);
    }

    public function getPermalinkForPost(Post $post)
    {
        return $this->getPermalink()->parse($post, $this->permalinkOptions['param']);
    }
}