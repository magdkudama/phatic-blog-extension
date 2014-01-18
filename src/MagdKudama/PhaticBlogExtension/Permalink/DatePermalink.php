<?php

namespace MagdKudama\PhaticBlogExtension\Permalink;

use DateTime;
use MagdKudama\PhaticBlogExtension\Model\Post;

class DatePermalink implements PermalinkExtension
{
    public function getName()
    {
        return 'date';
    }

    public function parse(Post $post, $parameter)
    {
        /** @var $createdAt DateTime */
        $createdAt = $post->getCreatedAt();

        return $createdAt->format($parameter);
    }
}