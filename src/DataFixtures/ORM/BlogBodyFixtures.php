<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\BlogBundle\Entity\Body;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BlogBodyFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogBodyFixtures extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    protected function doLoad(ObjectManager $manager)
    {
        foreach ($this->loadData() as $referenceGroup => $group) {
            if (isset($group['Tricks'])) {
                foreach ($group['Tricks'] as $referenceTrick => $trick) {
                    $body = new Body();
                    $body->setContent($trick['content']);
                    $manager->persist($body);
                    $this->addReference(implode('-', [$referenceGroup, $referenceTrick, 'body']), $body);
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
        $resources = Yaml::parse(file_get_contents(dirname(__DIR__) . '/BlogData.yml'));

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
