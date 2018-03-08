<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\BlogBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class BlogpostCategoryFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogpostCategoryFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $names = [
            'Gra' => 'Grabs',
            'Rot' => 'Rotations',
            'Fli' => 'Flips',
            'Sli' => 'Slides',
            'Old' => 'Old School',
        ];

        foreach ($names as $index => $name) {
            $category = new Category();
            $category->setName($name);

            $manager->persist($category);

            $this->addReference($index, $category);
        }
        $manager->flush();
    }
}
