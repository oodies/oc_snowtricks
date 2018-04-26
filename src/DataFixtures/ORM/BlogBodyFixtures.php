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
        // Generate a randomize content
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerLorem($faker));
        $content = implode(chr(10).chr(13), $faker->paragraphs(10));

        foreach ($this->loadData() as $referenceGroup => $group) {
            if (isset($group['Tricks'])) {
                foreach ($group['Tricks'] as $referenceTrick => $trick) {
                    $body = new Body();
                    if (empty($trick['content'])) {
                        $body->setContent($content);
                    } else {
                        $body->setContent($trick['content']);
                    }
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
