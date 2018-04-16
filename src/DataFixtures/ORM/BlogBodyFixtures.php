<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator as FakerGenerator;
use Faker\Provider\Lorem as FakerLorem;
use Ood\BlogBundle\Entity\Body;

/**
 * Class BlogBodyFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogBodyFixtures extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    protected function doLoad(ObjectManager $manager)
    {
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerLorem($faker));
        for ($i=1 ; $i <= 50; $i++) {
            $body = new Body();
            $body->setContent($faker->paragraph(5));

            $manager->persist($body);

            $this->addReference('body_' . (string)$i, $body);
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
