<?php

namespace MagdKudama\PhaticBlogExtension;

class Utils
{
    public static function stripSlash($word)
    {
        if (substr($word, 0, 1) == '/') {
            return substr($word, 1);
        }

        return $word;
    }
}