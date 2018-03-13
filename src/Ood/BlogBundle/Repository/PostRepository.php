<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/01
 */

namespace Ood\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    /**
     * @
     *  @param array        $params
     *      - "offset", Pagination start index
     *      - "limit", Maximum number of results from index
     *
     * @return mixed
     */
    public function findResources(array $params)
    {
        $qb = $this->createQueryBuilder('P');
        $this->wherePaginator($qb, $params);

        return $qb->getQuery()->getResult();
    }

    /**
     * Obtain the number of items
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return int
     */
    public function getNbItems()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('COUNT(R)')
           ->from('OodBlogBundle:Post', 'R');

        try {
            $numberOfEvents = (int)$qb->getQuery()->getSingleScalarResult();
        } catch (\Exception $e) {
            $numberOfEvents = 0;
        }

        return (int)$numberOfEvents;
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