<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\AppBundle\Services\Paginate;

use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * Class Paginator
 *
 * @package Ood\AppBundle\Services\Paginate;
 */
class Paginator
{
    /**
     * @param QueryBuilder $qb
     * @param int          $maxPerPAge
     * @param int          $currentPage
     *
     * @return Pagerfanta
     * @throws \LogicException
     */
    public function paginate(QueryBuilder $qb, $maxPerPage, $currentPage): Pagerfanta
    {
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));
        $pager
            ->setMaxPerPage($maxPerPage)
            ->setCurrentPage($currentPage);

        return $pager;
    }
}
