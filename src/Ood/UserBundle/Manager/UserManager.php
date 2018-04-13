<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\UserBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserManager
 *
 * @package Ood\UserBundle\Manager
 */
class UserManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    protected $encoder;

    /** *******************************
     *  METHODS
     */

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface       $em
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->em = $em;
        $this->repository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @param $token
     *
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByConfirmationToken($token)
    {
        return $this->repository->findByConfirmationToken($token);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param User $user
     */
    public function register(User $user)
    {
        $encodedPassword = $this->encoder->encodePassword($user, $user->getPlainPassword());

        $user->setRoles(['ROLE_AUTHOR'])
             ->setLocked(true)
             ->setPassword($encodedPassword)
             ->setConfirmationToken($this->getToken())
             ->setPlainPassword(null);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param $email
     *
     * @return null|User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($email): ?User
    {
        return $this->repository->loadUserByUsername($email);
    }

    /**
     * @param User $user
     */
    public function confirm(User $user)
    {
        $user->setConfirmationToken(null)
             ->setLocked(false);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function changePassword(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()))
             ->setPlainPassword(null)
             ->setConfirmationToken(null);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function confirmationToken(User $user)
    {
        $user->setConfirmationToken($this->getToken());
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function lock(User $user)
    {
        $user->setUpdateAt(new \DateTime())
             ->setLocked(true);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function unlock(User $user)
    {
        $user->setUpdateAt(new \DateTime())
             ->setLocked(false);

        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $user->setUpdateAt(new \DateTime());
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @return string
     */
    protected function getToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(20)), '+/', '-_'), '=');
    }
}
