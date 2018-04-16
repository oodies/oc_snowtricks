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

use Ood\BlogBundle\Entity\Post;

/**
 * Class BlogPostFixtures
 *
 * @package DataFixtures\ORM
 */
class BlogPostFixtures extends AbstractFixture implements DependentFixtureInterface
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
            BlogBodyFixtures::class,
            BlogCategoryFixtures::class,
            BlogHeaderFixtures::class,
            PictureImageFixtures::class,
            PictureVideoFixtures::class,
            UserUserFixtures::class
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function doLoad(ObjectManager $manager)
    {
        $jump = 26;
        for ($i = 1; $i <= 50; $i++) {
            $post = new Post();
            /** @var \Ood\BlogBundle\Entity\Category $category */
            $category = $this->getReference('category_' . (string)rand(1, 10));
            /** @var \Ood\UserBundle\Entity\User $blogger */
            $blogger = $this->getReference('user_blogger');
            /** @var \Ood\BlogBundle\Entity\Header $header */
            $header = $this->getReference('header_' . $i);
            /** @var \Ood\BlogBundle\Entity\Body $body */
            $body = $this->getReference('body_' . $i);

            $post
                ->setCategory($category)
                ->setBlogger($blogger)
                ->setHeader($header)
                ->setBody($body);

            for ($v = 0; $v <= 4; $v++) {
                /** @var \Ood\PictureBundle\Entity\Image $image */
                $image = $this->getReference('image_' . (string)($jump+$v));
                /** @var \Ood\PictureBundle\Entity\Video $video */
                $video = $this->getReference('video_' . (string)($jump+$v));

                $post->addImage($image)
                     ->addVideo($video);
            }

            $manager->persist($post);
            $this->addReference('post_' . (string)$i, $post);

            $jump = $jump + 5;
        }
        $manager->flush();
    }

    /**
     * @return array
     */
    protected function getEnvironments(): array
    {
        return ['dev'];
    }
}
