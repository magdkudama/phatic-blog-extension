<?php

namespace MagdKudama\PhaticBlogExtension\View;

use DateTime;
use MagdKudama\PhaticBlogExtension\Model\Post;
use MagdKudama\PhaticBlogExtension\Parser\BaseParser;
use MagdKudama\PhaticBlogExtension\Permalink\PermalinkGuesser;
use MagdKudama\PhaticBlogExtension\Utils;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class ViewExtension extends Twig_Extension
{
    protected $container;

    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('base_url', [$this, 'getBaseUrl']),
            new Twig_SimpleFunction('url', [$this, 'getUrl']),
            new Twig_SimpleFunction('url_for_post', [$this, 'getUrlForPost']),
            new Twig_SimpleFunction('post_content_route', [$this, 'getPostContentRoute']),
            new Twig_SimpleFunction('asset', [$this, 'asset'])
        ];
    }

    public function getGlobals()
    {
        /** @var BaseParser $postsReader */
        $postsReader = $this->container->get('blog_posts_collection');
        /** @var BaseParser $postsReader */
        $pagesReader = $this->container->get('blog_pages_collection');

        return [
            'posts' => $postsReader->read(),
            'pages' => $pagesReader->read()
        ];
    }

    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('stripped', [$this, 'strip']),
            new Twig_SimpleFilter('summarize', [$this, 'summarize'])
        ];
    }

    public function getBaseUrl()
    {
        return $this->container->getParameter('phatic.blog.base_url');
    }

    /**
     * @param $uri
     * @return string
     */
    public function getUrl($uri)
    {
        $base = $this->getBaseUrl();
        if (substr($base, -1) != '/') {
            $base .= '/';
        }

        return $base . $uri;
    }

    public function getUrlForPost(Post $post)
    {
        /** @var PermalinkGuesser $permalinkGuesser */
        $permalinkGuesser = $this->container->get('blog_permalink_guesser');

        return $this->getUrl($permalinkGuesser->getPermalinkForPost($post) . $post->getSlug());
    }

    public function getPostContentRoute(Post $post)
    {
        return $post->getFile()->getFilename() . '/content.html';
    }

    public function asset($asset, $version = true)
    {
        $asset = $this->getUrl('assets') . '/' . Utils::stripSlash($asset);

        if (!$version) {
            return $asset;
        }

        $date = new DateTime();
        return $asset . '?v=' . $date->format('YmdHis');
    }

    public function strip($text)
    {
        return strip_tags($text);
    }

    public function summarize($text, $quantity)
    {
        if (strlen($text) <= $quantity) {
            return $text;
        }

        return substr($text, 0, $quantity) . '...';
    }

    public function getName()
    {
        return 'blog.view.extension';
    }
}