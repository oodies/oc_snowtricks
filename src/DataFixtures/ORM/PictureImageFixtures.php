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
use Ood\PictureBundle\Entity\Image;

/**
 * Class PictureImageFixtures
 *
 * @package DataFixtures\ORM
 */
class PictureImageFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        $extension = ['jpg', 'png'];

        for ($i = 1; $i <= 60; $i++) {
            $image = new Image();
            $image->setExtension($extension[array_rand($extension, 1)]);
            if ($i <= 25) {
                $suffix = 'profile_';
            } else {
                $suffix = 'image_';
            }
            $image->setAlt($suffix . (string)$i);

            $manager->persist($image);

            $this->addReference('image_' . (string)$i, $image);
        }
        $manager->flush();
    }
}
