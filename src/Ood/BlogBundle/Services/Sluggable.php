<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogBundle\Services;

/**
 * Class Sluggable
 *
 * @package Ood\BlogBundle\Services
 */
class Sluggable
{
    /**
     * Slugify a text
     * Source https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     *
     * @param string $text
     *
     * @return string
     */
    public function slug(string $text): string
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
