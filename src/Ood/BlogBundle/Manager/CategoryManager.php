<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\BlogBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Ood\BlogBundle\Entity\Category;
use Ood\BlogBundle\Repository\CategoryRepository;

/**
 * Class CategoryManager
 *
 * @package Ood\BlogBundle\Manager
 */
class CategoryManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var CategoryRepository
     */
    protected $repository;

    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    /** *******************************
     *  METHODS
     */

    /**
     * CommentManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param CategoryRepository     $categoryRepository
     */
    public function __construct(EntityManagerInterface $em, CategoryRepository $categoryRepository)
    {
        $this->em = $em;
        $this->repository = $categoryRepository;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Add a category
     *
     * @param Category $category
     */
    public function add(Category $category)
    {
        $this->em->persist($category);
        $this->em->flush();
    }

    /**
     * Edit a category
     *
     * @param Category $category
     */
    public function edit(Category $category)
    {
        $this->em->persist($category);
        $this->em->flush();
    }
}
