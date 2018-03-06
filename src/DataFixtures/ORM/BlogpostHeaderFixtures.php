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
use Ood\BlogBundle\Entity\Header;

/**
 * Class BlogpostHeaderFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogpostHeaderFixtures extends Fixture
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            'm2e' => ['mute', 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant'],
            's1d' => ['sad', 'saisie de la carre backside de la planche, entre les deux pieds, avec la main avant'],
            'i2y' => [
                'indy',
                'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière'
            ],
            'rot180' => ['180', 'un 180 désigne un demi-tour, soit 180 degrés d\'angle'],
            'rot360' => ['360', '360, trois six pour un tour complet'],
            'f9s' => ['front flips', 'Rotation en avant'],
            'b9s' => ['backs flips', 'Rotation en arrière'],
        ];

        foreach ($data as $index => [$title, $brief]) {
            $header = new Header();
            $header
                ->setTitle($title)
                ->setBrief($brief);

            $manager->persist($header);

            $this->addReference('header_' . $index, $header);
        }
        $manager->flush();
    }
}
