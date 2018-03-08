<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator as FakerGenerator;
use Faker\Provider\Lorem as FakerLorem;
use Ood\BlogBundle\Entity\Body;

/**
 * Class BlogpostBodyFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogpostBodyFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'm2e',
            's1d',
            'i2y',
            'rot180',
            'rot360',
            'f9s',
            'b9s',
        ];

        $faker = new FakerGenerator();
        $faker->addProvider(new FakerLorem($faker));
        foreach ($data as $index) {
            $body = new Body();
            $body->setContent($faker->paragraph(5));

            $manager->persist($body);

            $this->addReference('body_' . $index, $body);
        }
        $manager->flush();
    }
}
