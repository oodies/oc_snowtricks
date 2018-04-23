<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\BlogBundle\Manager;

use Ood\BlogBundle\Entity\Category;
use Ood\BlogBundle\Entity\Post;
use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Ood\BlogBundle\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class PostManager
 *
 * @package Ood\BlogBundle\Manager
 */
class PostManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var PostRepository
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
     * @param PostRepository         $postRepository
     */
    public function __construct(EntityManagerInterface $em, PostRepository $postRepository)
    {
        $this->em = $em;
        $this->repository = $postRepository;
    }

    /**
     * @param $uniqueID
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getByUniqueID($uniqueID)
    {
        return $this->repository->getByUniqueID($uniqueID);
    }

    /**
     * @param $maxPerPage
     * @param $currentPage
     *
     * @return \Pagerfanta\Pagerfanta
     *
     * @throws \LogicException
     */
    public function findAllWithPaginate($maxPerPage, $currentPage)
    {
        return $this->repository->findAllWithPaginate($maxPerPage, $currentPage);
    }


    /**
     * @param Category $category
     * @param $maxPerPage
     * @param $currentPage
     *
     * @return \Pagerfanta\Pagerfanta
     *
     * @throws \LogicException
     */
    public function findAllByCategoryWithPaginate(Category $category, $maxPerPage, $currentPage)
    {
        return $this->repository->findAllByCategoryWithPaginate($category, $maxPerPage, $currentPage);
    }

    /**
     * Add a post
     *
     * @param Post $post
     */
    public function add(Post $post)
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * Update a post
     *
     * @param Post $post
     */
    public function update(Post $post)
    {
        $post->setUpdateAt(new \DateTime());
        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * @param Post $post
     */
    public function remove(Post $post)
    {
        $thread = $this->em->getRepository(Thread::class)->find($post->getIdPost());

        if ($thread) {
            $repositoryComment = $this->em->getRepository(Comment::class);
            $comments = $repositoryComment->findBy(['thread' => $thread]);

            foreach ($comments as $comment) {
                $this->em->remove($comment);
            }
            $this->em->remove($thread);
        }
        $this->em->remove($post);
        $this->em->flush();
    }
}
