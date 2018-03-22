<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogBundle\Services;

/**
 * Class Utils
 *
 * @package Ood\BlogBundle\Services
 */
class Utils
{
    /**
     * Generate tinyUrl
     *
     * @param int $identifier
     *
     * @return string
     */
    public function tinyUrl(int $identifier): string
    {
        return rtrim(strtr(base64_encode(crypt($identifier, md5($identifier))), '+/', '-_'), '=');
    }
}
