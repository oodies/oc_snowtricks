<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\BlogBundle\EventListener\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ood\BlogBundle\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PostListener
 *
 * @package Ood\BlogBundle\EventListener\Entity
 */
class PostListener
{
    /** *******************************
     *  PROPERTIES
     */

    /** @var TokenStorageInterface */
    protected $tokenStorage;

    /** *******************************
     *  METHODS
     */

    /**
     * PostListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param Post $post
     *
     * @ORM\PrePersist()
     */
    public function onPrePersist(Post $post)
    {
        // if data fixtures
        if (!$post->getBlogger() instanceof UserInterface) {
            $post->setBlogger($this->getUser());
        }
    }

    /**
     * @return null|UserInterface
     */
    protected function getUser(): ?UserInterface
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}
