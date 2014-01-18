<?php

namespace MagdKudama\PhaticBlogExtension\Permalink;

use MagdKudama\PhaticBlogExtension\Model\Post;

class PrefixPermalink implements PermalinkExtension
{
    public function getName()
    {
        return 'prefix';
    }

    public function parse(Post $post, $parameter)
    {
        return $parameter;
    }
}