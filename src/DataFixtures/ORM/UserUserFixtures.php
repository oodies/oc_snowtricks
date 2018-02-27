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
use Ood\UserBundle\Entity\User;
use Faker\Generator as FakerGenerator;
use Faker\Provider\fr_FR\Person as FakerPerson;
use Faker\Provider\fr_FR\Internet as FakerInternet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class LoadUserData
 *
 * @package DataFixtures\ORM
 */
class UserUserFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $encoder;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [ PictureImageFixtures::class ];
    }


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
                 ->setRoles(['ROLE_USER']);

            $hashPassword = $this->encoder->encodePassword($user, '12345pass');
            $user->setPassword($hashPassword);
            /** @var \Ood\PictureBundle\Entity\Image $image */
            $image = $this->getReference('image_'.(string)$i);
            $user->setPhoto($image);

            $manager->persist($user);

            $this->addReference('user_' . (string)$i, $user);
        }

        $manager->persist($this->createJDoeUser());

        $manager->flush();
    }

    /**
     * @param ContainerInterface|null $container
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->encoder = $this->container->get('security.password_encoder');
    }

    /**
     * @return User
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     */
    protected function createJDoeUser(): User
    {
        $user = new User();
        $user->setLastname('doe')
             ->setFirstname('joe')
             ->setUsername('jdoe')
             ->setEmail('john.doe@email.com')
             ->setRoles(['ROLE_USER']);

        $hashPassword = $this->encoder->encodePassword($user, '12345pass');
        $user->setPassword($hashPassword);

        $this->addReference('user_jdoe', $user);

        return $user;
    }
}
