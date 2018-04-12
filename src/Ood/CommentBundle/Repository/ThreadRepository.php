<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\CommentBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ood\CommentBundle\Entity\Thread;
use Symfony\Bridge\Doctrine\RegistryInterface;


/**
 * Class ThreadRepository
 *
 * @package Ood\ThreadBundle\Repository
 */
class ThreadRepository extends ServiceEntityRepository
{
    /**
     * ThreadRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Thread::class);
    }
}
