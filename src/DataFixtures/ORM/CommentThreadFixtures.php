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
use Ood\CommentBundle\Entity\Thread;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CommentThreadFixtures
 *
 * @package DataFixtures\ORM
 */
class CommentThreadFixtures extends AbstractFixture implements DependentFixtureInterface
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
            BlogPostFixtures::class
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @throws \Exception
     */
    public function doLoad(ObjectManager $manager)
    {
        foreach ($this->loadData() as $referenceGroup => $group) {
            if (isset($group['Tricks'])) {
                foreach ($group['Tricks'] as $referenceTrick => $trick) {
                    if (isset($trick['Comments'])) {
                        $thread = new Thread();
                        /** @var \Ood\BlogBundle\Entity\Post $post */
                        $post = $this->getReference(implode('-', [$referenceGroup, $referenceTrick, 'post']));
                        $thread->setIdThread($post->getIdPost())
                               ->setNumberOfComment(count($trick['Comments']));

                        $manager->persist($thread);
                        $this->addReference(implode('-', [$referenceGroup, $referenceTrick, 'thread']), $thread);
                    }
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
