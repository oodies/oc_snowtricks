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

        for ($i = 1; $i <= 250; $i++) {
            $video = new Video();
            $platform = $platforms[array_rand($platforms, 1)];

            switch ($platform) {
                case 'youtube':
                    $identifier = 'mH8-x5U7XsA';
                    $url = 'https://youtu.be/'.$identifier;
                    break;
                case 'dailymotion':
                    $identifier = 'x3rqaoa';
                    $url = 'https://dai.ly/'.$identifier;
                    break;
                case 'vimeo':
                    $identifier = '19314230';
                    $url = 'https://vimeo.com/'.$identifier;
                    break;
            }
            $video->setPlatform($platform)
                  ->setIdentifier($identifier)
                  ->setUrl($url);

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
