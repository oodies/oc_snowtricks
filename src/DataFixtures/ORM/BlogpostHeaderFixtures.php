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
use Ood\BlogpostBundle\Entity\Header;

/**
 * Class BlogpostHeaderFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogpostHeaderFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'post_m2e' => ['mute', 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant'],
            'post_s1d' => ['sad', 'saisie de la carre backside de la planche, entre les deux pieds, avec la main avant'],
            'post_i2y' => ['indy', 'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière'],
            'post_rot180' => ['180', 'un 180 désigne un demi-tour, soit 180 degrés d\'angle'],
            'post_rot360' => ['360', '360, trois six pour un tour complet'],
            'post_f9s' => ['front flips', 'Rotation en avant'],
            'post_b9s' => ['backs flips', 'Rotation en arrière'],
        ];

        foreach ($data as $index => [$title, $brief]) {
            /** @var \Ood\BlogpostBundle\Entity\Post $post */
            $post = $this->getReference($index);

            $header = new Header();
            $header
                ->setTitle($title)
                ->setBrief($brief)
                ->setPost($post);

            $manager->persist($header);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            BlogpostPostFixtures::class
        ];
    }
}
