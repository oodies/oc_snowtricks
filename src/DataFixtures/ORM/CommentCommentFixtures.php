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
use Faker\Generator as FakerGenerator;
use Faker\Provider\Lorem as FakerLorem;
use Ood\CommentBundle\Entity\Comment;

/**
 * Class CommentCommentFixtures
 *
 * @package DataFixtures\ORM
 */
class CommentCommentFixtures extends Fixture implements DependentFixtureInterface
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
    public function load(ObjectManager $manager)
    {
        $data = [
            'm2e',
            's1d',
            'i2y',
            'f9s',
            'b9s',
        ];

        $faker = new FakerGenerator();
        $faker->addProvider(new FakerLorem($faker));

        foreach ($data as $index) {
            /** @var \Ood\CommentBundle\Entity\Thread $thread */
            $thread = $this->getReference('thread_' . $index);
            // 5 comments by thread
            for ($i=1; $i<=5; $i++) {
                $comment = new Comment();
                /** @var \Ood\UserBundle\Entity\User $author */
                $author = $this->getReference('user_' . (string)rand(6, 25));
                $comment
                    ->setThread($thread)
                    ->setBody($faker->paragraph(1))
                    ->setAuthor($author)
                    ->setEnabled(rand(0,1));

                $manager->persist($comment);
            }
        }
        $manager->flush();
    }
}
