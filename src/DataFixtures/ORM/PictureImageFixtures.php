<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\PictureBundle\Entity\Image;

/**
 * Class PictureImageFixtures
 *
 * @package DataFixtures\ORM
 */
class PictureImageFixtures extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function doLoad(ObjectManager $manager)
    {
        $extension = ['jpg', 'png'];

        for ($i = 1; $i <= 300; $i++) {
            $image = new Image();
            $image->setExtension($extension[array_rand($extension, 1)]);
            $refName = 'image_' . (string)$i;
            $image->setAlt($refName);

            $manager->persist($image);

            $this->addReference($refName, $image);
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
