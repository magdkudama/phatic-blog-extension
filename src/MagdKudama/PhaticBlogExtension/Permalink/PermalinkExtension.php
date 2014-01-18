<?php

namespace MagdKudama\PhaticBlogExtension\Permalink;

use MagdKudama\PhaticBlogExtension\Model\Post;

interface PermalinkExtension
{
    /** @return string */
    function getName();

    /**
     * @param Post $post
     * @param $parameter
     * @return string
     */
    function parse(Post $post, $parameter);
}