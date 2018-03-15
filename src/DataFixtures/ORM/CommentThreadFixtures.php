<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ood\CommentBundle\Entity\Thread;

/**
 * Class CommentThreadFixtures
 *
 * @package DataFixtures\ORM
 */
class CommentThreadFixtures extends Fixture implements DependentFixtureInterface
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
        for ($i = 1; $i <= 50; $i++) {
            $thread = new Thread();
            /** @var \Ood\BlogBundle\Entity\Post $post */
            $post = $this->getReference('post_' . $i);
            $thread->setIdThread($post->getIdPost())
                   ->setNumberOfComment(rand(1, 5));

            $manager->persist($thread);

            $this->addReference('thread_' . (string)$i, $thread);
        }
        $manager->flush();
    }
}
