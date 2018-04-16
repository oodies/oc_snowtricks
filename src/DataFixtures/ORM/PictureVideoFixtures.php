<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\PictureBundle\Entity\Video;

/**
 * Class PictureVideoFixtures
 *
 * @package DataFixtures\ORM
 */
class PictureVideoFixtures extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function doLoad(ObjectManager $manager)
    {
        $platforms = ['dailymotion', 'vimeo', 'youtube'];

        for ($i = 1; $i <= 300; $i++) {
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
     * @return array
     */
    protected function getEnvironments(): array
    {
        return ['dev'];
    }
}
