<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Generator as FakerGenerator;
use Faker\Provider\Lorem as FakerLorem;
use Ood\BlogpostBundle\Entity\Body;

/**
 * Class BlogpostBodyFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogpostBodyFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [
            BlogpostPostFixtures::class
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'post_m2e',
            'post_s1d',
            'post_i2y',
            'post_rot180',
            'post_rot360',
            'post_f9s',
            'post_b9s',
        ];

        $faker = new FakerGenerator();
        $faker->addProvider(new FakerLorem($faker));
        foreach ($data as $index) {
            $body = new Body();
            /** @var \Ood\BlogpostBundle\Entity\Post $post */
            $post = $this->getReference($index);
            $body
                ->setContent($faker->paragraph(5))
                ->setPost($post);

            $manager->persist($body);
        }
        $manager->flush();
    }
}
