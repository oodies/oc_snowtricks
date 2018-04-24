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
use Ood\PictureBundle\Entity\Image;
use Symfony\Component\Yaml\Yaml;

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
     *
     * @throws \Symfony\Component\Yaml\Exception\ParseException
     */
    public function doLoad(ObjectManager $manager)
    {
        foreach ($this->loadData() as $referenceGroup => $group) {
            if (isset($group['Tricks'])) {
                foreach ($group['Tricks'] as $referenceTrick => $trick) {
                    $post = new Post();
                    /** @var \Ood\BlogBundle\Entity\Category $category */
                    $category = $this->getReference($referenceGroup);
                    /** @var \Ood\UserBundle\Entity\User $blogger */
                    $blogger = $this->getReference('user_blogger');
                    /** @var \Ood\BlogBundle\Entity\Header $header */
                    $header = $this->getReference(implode('-', [$referenceGroup, $referenceTrick, 'header']));
                    /** @var \Ood\BlogBundle\Entity\Body $body */
                    $body = $this->getReference(implode('-', [$referenceGroup, $referenceTrick, 'body']));

                    $post
                        ->setCategory($category)
                        ->setBlogger($blogger)
                        ->setHeader($header)
                        ->setBody($body);

                    if (isset($trick['Videos'])) {
                        foreach ($trick['Videos'] as $referenceVideo => $data) {
                            /** @var \Ood\PictureBundle\Entity\Video $video */
                            $video = $this->getReference(
                                implode('-', [$referenceGroup, $referenceTrick, $referenceVideo])
                            );
                            $post->addVideo($video);
                        }
                    }

                    if (isset($trick['Pictures'])) {
                        foreach ($trick['Pictures'] as $filename) {
                            list($name, $ext) = explode('.', $filename);
                            /** @var Image $image */
                            $image = $this->getReference(implode('-', [$referenceGroup, $referenceTrick, $name]));
                            $post->addImage($image);
                        }
                    }

                    $manager->persist($post);
                    $this->addReference(implode('-', [$referenceGroup, $referenceTrick, 'post']), $post);
                }
            }
        }
        $manager->flush();
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
