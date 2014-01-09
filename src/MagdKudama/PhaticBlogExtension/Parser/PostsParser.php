<?php

namespace MagdKudama\PhaticBlogExtension\Parser;

use MagdKudama\PhaticBlogExtension\Model\Collection\PostCollection;
use MagdKudama\PhaticBlogExtension\Model\Post;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;
use DateTime;

class PostsParser extends BaseParser
{
    public function read()
    {
        $postsCollection = new PostCollection();

        /** @var SplFileInfo $postData */
        foreach ($this->getSearchCriteria() as $postData) {
            $configFile = $postData->getRealpath() . '/config.yml';
            $body = $postData->getRealpath() . '/content.html';
            $config = Yaml::parse($configFile)['config'];

            $post = new Post();

            $post->setTitle($config['title']);
            $post->setCreatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $config['created_at']));
            $post->setContent(file_get_contents($body));
            $post->setKeywords($config['keywords']);
            $post->setMetaDescription($config['description']);
            $post->setSlug($postData->getFilename());
            $post->setFile($postData);

            $postsCollection->add($post);
        }

        return $postsCollection->orderByDate();
    }

    protected function getSearchCriteria()
    {
        return $this->getFinder()->directories()->notName('_*')->in($this->getConfig()->getPostsPath());
    }
}