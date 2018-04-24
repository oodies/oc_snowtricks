<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\BlogBundle\Entity\Header;
use Symfony\Component\Yaml\Yaml;

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
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function doLoad(ObjectManager $manager)
    {
        foreach ($this->loadData() as $referenceGroup => $group) {
            if (isset($group['Tricks'])) {
                foreach ($group['Tricks'] as $referenceTrick => $trick) {
                    $header = new Header();
                    $header
                        ->setTitle($trick['title'])
                        ->setBrief($trick['brief'])
                        ->setSlug($trick['slug']);
                    $manager->persist($header);
                    $this->addReference(implode('-', [$referenceGroup, $referenceTrick, 'header']), $header);
                }
            }
            $manager->flush();
        }
    }

    /**
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    protected function loadData()
    {
        $resources = Yaml::parse(file_get_contents(dirname(__DIR__) . '\BlogData.yml'));

        return $resources['Groups'];
    }

    /**
     * @return array
     */
    protected function getEnvironments(): array
    {
        return ['dev', 'prod'];
    }
}
