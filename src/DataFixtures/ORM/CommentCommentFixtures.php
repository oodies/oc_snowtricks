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
use Faker\Generator as FakerGenerator;
use Faker\Provider\Lorem as FakerLorem;
use Ood\CommentBundle\Entity\Comment;

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
     */
    public function doLoad(ObjectManager $manager)
    {
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerLorem($faker));

        for ($i = 1; $i <= 50; $i++) {
            /** @var \Ood\CommentBundle\Entity\Thread $thread */
            $thread = $this->getReference('thread_' . $i);
            // 5 comments by thread
            for ($j = 1; $j <= 40; $j++) {
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
                    ->setBody($faker->paragraph(1))
                    ->setAuthor($author)
                    ->setUpdateAt($date)
                    ->setCreateAt($date)
                    ->setEnabled(rand(0, 1));

                $manager->persist($comment);
            }
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
