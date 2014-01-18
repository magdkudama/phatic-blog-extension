<?php

namespace MagdKudama\PhaticBlogExtension\Processor;

use MagdKudama\Phatic\Processor;
use MagdKudama\PhaticBlogExtension\Event\BasePostEvent;
use MagdKudama\PhaticBlogExtension\Event\Events;
use MagdKudama\PhaticBlogExtension\Model\Post;
use MagdKudama\PhaticBlogExtension\Parser\BaseParser;
use MagdKudama\PhaticBlogExtension\Permalink\PermalinkGuesser;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class PostProcessor extends BaseProcessor implements Processor
{
    public function getCollection()
    {
        /** @var BaseParser $collection */
        $collection = $this->container->get('blog_posts_collection');
        return $collection->read();
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

        /** @var PermalinkGuesser $permalinkGuesser */
        $permalinkGuesser = $this->container->get('blog_permalink_guesser');

        $postPath = $this->getConfig()->getResultsPath() . $permalinkGuesser->getPermalinkForPost($post) . $post->getSlug();

        $this->getFileSystem()->mkdir($postPath);

        $this->getDispatcher()->dispatch(Events::BEFORE_POST_CREATED, $event);
        $this->getFileSystem()->dumpFile($postPath . '/index.html', $post->getPageContent());
    }
}