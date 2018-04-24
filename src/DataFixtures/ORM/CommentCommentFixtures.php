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
use Ood\CommentBundle\Entity\Comment;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CommentCommentFixtures
 *
 * @package DataFixtures\ORM
 */
class CommentCommentFixtures extends AbstractFixture implements DependentFixtureInterface
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
            CommentThreadFixtures::class,
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
                    if (isset($trick['Comments'])) {
                        /** @var \Ood\CommentBundle\Entity\Thread $thread */
                        $thread = $this->getReference(implode('-', [$referenceGroup, $referenceTrick, 'thread']));
                        foreach ($trick['Comments'] as $data) {
                            $date = new \DateTime(
                                '@' . mktime(
                                    rand(7, 22),
                                    rand(0, 60),
                                    '0',
                                    rand(1, 12),
                                    rand(1, 30),
                                    rand('2016', '2017')
                                )
                            );
                            $comment = new Comment();
                            /** @var \Ood\UserBundle\Entity\User $author */
                            $author = $this->getReference('user_author');
                            $comment
                                ->setThread($thread)
                                ->setBody($data)
                                ->setAuthor($author)
                                ->setUpdateAt($date)
                                ->setCreateAt($date)
                                ->setEnabled(true);

                            $manager->persist($comment);
                        }
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
