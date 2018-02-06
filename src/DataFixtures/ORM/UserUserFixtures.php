<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Ood\UserBundle\Entity\User;
use Faker\Generator as FakerGenerator;
use Faker\Provider\fr_FR\Person as FakerPerson;
use Faker\Provider\fr_FR\Internet as FakerInternet;
use Doctrine\Bundle\FixturesBundle\Fixture;

/**
 * Class LoadUserData
 *
 * @package DataFixtures\ORM
 */
class UserUserFixtures extends Fixture
{
    /**
     * Performs the actual fixtures loading.
     *
     * @see \Doctrine\Common\DataFixtures\FixtureInterface::load()
     *
     * @param ObjectManager $manager The object manager.
     */
    public function Load(ObjectManager $manager)
    {
        $faker = new FakerGenerator();
        $faker->addProvider(new FakerPerson($faker));
        $faker->addProvider(new FakerInternet($faker));

        for ($i = 1; $i <= 25; $i++) {
            $user = new User();
            $user->setLastname($faker->lastName)
                 ->setFirstname($faker->firstName)
                 ->setUsername($faker->lastName)
                 ->setNickname($faker->firstName)
                 ->setEmail($faker->email)
                 ->setPassword('12345Toto');

            $manager->persist($user);

            $this->addReference('user_' . (string)$i, $user);
        }
        $manager->flush();
    }
}
