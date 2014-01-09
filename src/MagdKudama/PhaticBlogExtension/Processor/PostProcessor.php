<?php

namespace MagdKudama\PhaticBlogExtension\Processor;

use MagdKudama\Phatic\AbstractProcessor;
use MagdKudama\PhaticBlogExtension\Event\BasePostEvent;
use MagdKudama\PhaticBlogExtension\Event\Events;
use MagdKudama\PhaticBlogExtension\Model\Post;
use MagdKudama\PhaticBlogExtension\Parser\PostsParser;

class PostProcessor extends AbstractProcessor
{
    private $postPrefix;
    private $posts;

    public function setPostPrefix($postPrefix)
    {
        $this->postPrefix = $postPrefix;
    }

    public function setCollection(PostsParser $posts)
    {
        $this->posts = $posts->read();
    }

    public function getCollection()
    {
        return $this->posts;
    }

    public function dump($post)
    {
        $this->dumpPost($post);
    }

    public function getName()
    {
        return 'post';
    }

    protected function dumpPost(Post $post)
    {
        $event = new BasePostEvent($post);

        $content = $this->getView()->render(
            '_layouts/post.html',
            [
                'post' => $post
            ]
        );

        $post->setPageContent($content);

        if (!empty($this->postPrefix)) {
            $posts = $this->getConfig()->getResultsPath() . $this->postPrefix . '/' . $post->getSlug();
        } else {
            $posts = $this->getConfig()->getResultsPath() . $post->getSlug();
        }

        $this->getFileSystem()->mkdir($posts);

        $this->getDispatcher()->dispatch(Events::BEFORE_POST_CREATED, $event);
        $this->getFileSystem()->dumpFile($posts . '/index.html', $post->getPageContent());
    }
}