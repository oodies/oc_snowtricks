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
use Symfony\Component\Yaml\Yaml;

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
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function doLoad(ObjectManager $manager)
    {
        foreach ($this->loadData() as $reference => $data) {
            $category = new Category();
            $category->setName($data['name'])
                     ->setSlug($data['slug'])
                     ->setDescription($data['description']);

            $manager->persist($category);
            $this->addReference($reference, $category);
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

    /**
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    protected function loadData()
    {
        $resources = Yaml::parse(file_get_contents(dirname(__DIR__) . '\BlogData.yml'));

        return $resources['Groups'];
    }
}
