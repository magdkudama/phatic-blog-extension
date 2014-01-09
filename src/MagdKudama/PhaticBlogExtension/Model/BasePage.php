<?php

namespace MagdKudama\PhaticBlogExtension\Model;

use Symfony\Component\Finder\SplFileInfo;

abstract class BasePage
{
    protected $pageContent;

    /** @var SplFileInfo */
    protected $file;

    /**
     * @param string $pageContent
     */
    public function setPageContent($pageContent)
    {
        $this->pageContent = $pageContent;
    }

    /**
     * @return string
     */
    public function getPageContent()
    {
        return $this->pageContent;
    }

    public function setFile(SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function __toString()
    {
        return $this->getFile()->getRelativePathname();
    }
}