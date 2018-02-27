<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ood\PictureBundle\Entity\Video;

/**
 * Class PictureVideoFixtures
 *
 * @package DataFixtures\ORM
 */
class PictureVideoFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $platforms = ['dailymotion', 'vimeo', 'youtube'];

        for ($i = 26; $i <= 60; $i++) {
            $video = new Video();
            $video->setPlatform($platforms[array_rand($platforms, 1)]);
            $video->setIdentifier($this->tinyUrl($i));

            $manager->persist($video);

            $this->addReference('video_' . (string)$i, $video);
        }

        $manager->flush();
    }

    /**
     * Generate tinyUrl
     *
     * @param int $id
     *
     * @return string
     */
    private function tinyUrl(int $id): string
    {
        return rtrim(strtr(base64_encode(crypt($id, md5($id))), '+/', '-_'), '=');
    }
}
