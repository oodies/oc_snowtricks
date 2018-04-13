<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\CommentBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Ood\AppBundle\Services\Paginate\Paginator;
use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class CommentRepository
 *
 * @package Ood\CommentBundle\Repository
 */
class CommentRepository extends ServiceEntityRepository
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * CommentRepository constructor.
     *
     * @param RegistryInterface $registry
     * @param Paginator         $paginator
     */
    public function __construct(RegistryInterface $registry, Paginator $paginator)
    {
        $this->paginator = $paginator;

        parent::__construct($registry, Comment::class);
    }

    /**
     * Obtain the enable comment by thread with pagination option
     *
     * @param Thread   $thread
     * @param int|null $maxPerPage
     * @param int|null $currentPage
     *
     * @throws \LogicException
     *
     * @return Pagerfanta
     */
    public function findAllByThreadWithPaginate(Thread $thread, $maxPerPage = null, $currentPage = null): Pagerfanta
    {
        $qb = $this->createQueryBuilder('C');

        $qb->where('C.thread = :thread')
           ->andWhere('C.enabled = :enabled')
           ->orderBy('C.createAt', 'DESC');

        $qb->setParameter('thread', $thread)
           ->setParameter('enabled', true);

        return $this->paginator->paginate($qb, $maxPerPage, $currentPage);
    }

    /**
     * Obtain the number of enable comment by thread
     *
     * @param Thread $thread
     *
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNbCommentsByThread(Thread $thread): int
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('COUNT(R)')
           ->from('OodCommentBundle:Comment', 'R')
           ->where('R.thread = :thread')
           ->andWhere('R.enabled = :enabled');

        $qb->setParameter('thread', $thread)
           ->setParameter('enabled', true);

        return $numberOf = (int)$qb->getQuery()->getSingleScalarResult();
    }
}
