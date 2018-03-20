<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\CommentBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Ood\CommentBundle\Entity\Thread;

/**
 * Class CommentRepository
 *
 * @package Ood\CommentBundle\Repository
 */
class CommentRepository extends EntityRepository
{
    /**
     * Obtain the enable comment by thread with pagination option
     *
     * @param Thread $thread
     *
     * @param array  $params
     *      - "offset", Pagination start index
     *      - "limit", Maximum number of results from index
     *
     * @return mixed
     */
    public function findByThread(Thread $thread, array $params)
    {
        $qb = $this->createQueryBuilder('C');
        $this->wherePaginator($qb, $params);

        $qb->where('C.thread = :thread')
           ->andWhere('C.enabled = :enabled')
           ->orderBy('C.createAt', 'DESC');

        $qb->setParameter('thread', $thread)
           ->setParameter('enabled', true);

        return $qb->getQuery()->getResult();
    }

    /**
     * Obtain the number of enable comment by thread
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
    public function getNbCommentsByThread(Thread $thread)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('COUNT(R)')
           ->from('OodCommentBundle:Comment', 'R')
           ->where('R.thread = :thread')
           ->andWhere('R.enabled = :enabled');

        $qb->setParameter('thread', $thread)
           ->setParameter('enabled', true);

        try {
            $numberOf= (int)$qb->getQuery()->getSingleScalarResult();
        } catch (\Exception $e) {
            $numberOf = 0;
        }

        return $numberOf;
    }

    /**
     * @param QueryBuilder $qb
     * @param array        $params
     *      - "offset", Pagination start index
     *      - "limit", Maximum number of results from index
     */
    private function wherePaginator(QueryBuilder $qb, array $params)
    {
        if (is_numeric($params['offset']) && $e = isset($params['offset'])) {
            $qb->setFirstResult($params['offset']);
        }

        if (is_numeric($params['limit']) && $e = isset($params['limit'])) {
            $qb->setMaxResults($params['limit']);
        }
    }
}
