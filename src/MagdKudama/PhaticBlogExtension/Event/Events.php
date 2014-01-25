<?php

namespace MagdKudama\PhaticBlogExtension\Event;

final class Events
{
    const BEFORE_POST_CREATED = 'blog.before.post_created';
    const BEFORE_PAGE_CREATED = 'blog.before.page_created';
    const BEFORE_HOMEPAGE_CREATED = 'blog.before.homepage_created';
    const AFTER_DUMP_ASSETS = 'blog.before.dump_assets';
}