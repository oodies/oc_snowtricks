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

/**
 * Class BlogCategoryFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogCategoryFixtures extends AbstractFixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function doLoad(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $index = 'category_' . (string)$i;
            $category->setName($index);

            $manager->persist($category);

            $this->addReference($index, $category);
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    protected function getEnvironments(): array
    {
        return ['dev', 'prod'];
    }
}
