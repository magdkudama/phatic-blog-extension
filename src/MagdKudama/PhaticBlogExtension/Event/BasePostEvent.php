<?php

namespace MagdKudama\PhaticBlogExtension\Event;

use MagdKudama\PhaticBlogExtension\Model\Post;
use Symfony\Component\EventDispatcher\Event;

class BasePostEvent extends Event
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /** @return Post */
    public function getPost()
    {
        return $this->post;
    }
}