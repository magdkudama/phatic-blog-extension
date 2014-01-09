<?php

namespace MagdKudama\PhaticBlogExtension\Event;

use MagdKudama\PhaticBlogExtension\Model\Page;
use Symfony\Component\EventDispatcher\Event;

class BasePageEvent extends Event
{
    protected $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    /** @return Page */
    public function getPage()
    {
        return $this->page;
    }
}