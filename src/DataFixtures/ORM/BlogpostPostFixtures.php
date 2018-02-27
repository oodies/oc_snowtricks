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

use Ood\BlogpostBundle\Entity\Post;

/**
 * Class BlogpostPostFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogpostPostFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            BlogpostBodyFixtures::class,
            BlogpostCategoryFixtures::class,
            BlogpostHeaderFixtures::class,
            PictureImageFixtures::class,
            UserUserFixtures::class
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
            'm2e'    => 'Gra',
            's1d'    => 'Gra',
            'i2y'    => 'Gra',
            'rot180' => 'Rot',
            'rot360' => 'Rot',
            'f9s'    => 'Fli',
            'b9s'    => 'Fli',
        ];

        $i = 26;
        foreach ($data as $index => $category) {
            $post = new Post();
            /** @var \Ood\BlogpostBundle\Entity\Category $category */
            $category = $this->getReference($category);
            /** @var \Ood\UserBundle\Entity\User $blogger */
            $blogger = $this->getReference('user_' . (string)rand(1, 5));
            /** @var \Ood\BlogpostBundle\Entity\Header $header */
            $header = $this->getReference('header_' . $index);
            /** @var \Ood\BlogpostBundle\Entity\Body $body */
            $body = $this->getReference('body_' . $index);
            /** @var \Ood\PictureBundle\Entity\Image $image1 */
            $image1 = $this->getReference('image_' . (string)$i);
            /** @var \Ood\PictureBundle\Entity\Image $image2 */
            $image2 = $this->getReference('image_' . (string)($i + 1));

            $post
                ->setCategory($category)
                ->setBlogger($blogger)
                ->setHeader($header)
                ->setBody($body)
                ->addImage($image1)
                ->addImage($image2);

            $manager->persist($post);
            $this->addReference('post_' . $index, $post);

            $i = $i + 2;
        }
        $manager->flush();
    }
}
