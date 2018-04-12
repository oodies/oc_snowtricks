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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;


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

    /**
     * @var UserPasswordEncoder
     */
    protected $encoder;

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return [PictureImageFixtures::class];
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
            $email = $faker->email;
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;

            $user->setLastname($lastName)
                 ->setFirstname($firstName)
                 ->setUsername( mb_strtolower(substr($firstName,0,1) . $lastName))
                 ->setNickname($faker->firstName)
                 ->setEmail($email)
                 ->setRoles(['ROLE_AUTHOR']);

            $hashPassword = $this->encoder->encodePassword($user, '12345');
            $user->setPassword($hashPassword);
            /** @var \Ood\PictureBundle\Entity\Image $image */
            $image = $this->getReference('image_' . (string)$i);
            $user->setPhoto($image);

            $manager->persist($user);

            $this->addReference('user_' . (string)$i, $user);
        }

        $this->createSpecificUser($manager);

        $manager->flush();
    }

    /**
     * @param ContainerInterface|null $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->encoder = $this->container->get('security.password_encoder');
    }

    /**
     *
     * @param ObjectManager $manager
     */
    protected function createSpecificUser(ObjectManager $manager)
    {

        $users = [
            'user_jdoe' => ['joe', 'doe', 'ROLE_USER'],
            'user_author' => ['smith', 'author', 'ROLE_AUTHOR'],
            'user_blogger' => ['smith', 'blogger', 'ROLE_BLOGGER'],
            'user_admin' => ['smith', 'admin', 'ROLE_ADMIN']
        ];    

        foreach ($users as list($firstName, $lastName, $role)) {
            $user = new User();
            $user->setLastname($lastName)
                 ->setFirstname($firstName)
                 ->setUsername($lastName)
                 ->setNickname($lastName)
                 ->setEmail($firstName.'.'.$lastName.'@email.com')
                 ->setRoles([$role]);
    
            $hashPassword = $this->encoder->encodePassword($user, '12345');
            $user->setPassword($hashPassword);
    
            $this->addReference('user_'.$lastName, $user);

            $manager->persist($user);
        }
    }
}
