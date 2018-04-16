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
use Ood\BlogBundle\Entity\Header;

/**
 * Class BlogHeaderFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogHeaderFixtures extends AbstractFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function doLoad(ObjectManager $manager)
    {
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerLorem($faker));

        for ($i = 1; $i <= 50; $i++) {
            $header = new Header();
            $header
                ->setTitle('figure_' . $i)
                ->setBrief($faker->paragraph(1))
                ->setSlug('figure-' . $i);

            $manager->persist($header);

            $this->addReference('header_' . (string)$i, $header);
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
