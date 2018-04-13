<?php

namespace Ood\CommentBundle\Manager;

use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Ood\CommentBundle\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class CommentManager
 *
 * @package Ood\CommentBundle\Manager
 */
class CommentManager
{
    /** *******************************
     *  PROPERTIES
     */

    /**
     * @var CommentRepository
     */
    protected $repository;

    /** @var EntityManagerInterface $em */
    protected $em;

    /** *******************************
     *  METHODS
     */

    /**
     * CommentManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param CommentRepository      $commentRepository
     */
    public function __construct(EntityManagerInterface $em, CommentRepository $commentRepository)
    {
        $this->em = $em;
        $this->repository = $commentRepository;
    }

    /**
     * Find all comments
     *
     * @return array|Comment[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Find all comments by thread with paginate
     *
     * @param $thread
     * @param $maxPerPage
     * @param $currentPage
     *
     * @return \Pagerfanta\Pagerfanta
     * @throws \LogicException
     */
    public function findAllByThreadWithPaginate($thread, $maxPerPage, $currentPage)
    {
        return $this->repository->findAllByThreadWithPaginate($thread, $maxPerPage, $currentPage);
    }

    /**
     * Approve a comment
     *
     * @param FormInterface $form
     * @param Comment       $comment
     *
     * @throws \Exception
     */
    public function approve(Comment $comment)
    {
        $comment
            ->setEnabled(true)
            ->setUpdateAt(new \DateTime());

        $thread = $comment->getThread();
        $thread->commentCounter();

        $this->em->persist($comment);
        $this->em->persist($thread);
        $this->em->flush();
    }

    /**
     * Disapprove a comment
     *
     * @param Comment $comment
     *
     * @throws \Exception
     */
    public function disapprove(Comment $comment)
    {
        $comment
            ->setEnabled(false)
            ->setUpdateAt(new \DateTime());

        $thread = $comment->getThread();
        $thread->commentCounter(-1);

        $this->em->persist($comment);
        $this->em->persist($thread);
        $this->em->flush();
    }

    /**
     * Add a comment
     *
     * @param Comment       $comment
     * @param UserInterface $user
     * @param int           $threadId
     *
     * @throws \Exception
     */
    public function add(Comment $comment, UserInterface $user, int $threadId)
    {
        $thread = $this->em->getRepository(Thread::class)->find($threadId);

        if ($thread === null) {
            $thread = new Thread();
            $thread->setIdThread($threadId);
        }

        $thread->commentCounter(); // increment comment counter
        $comment->setThread($thread)
                ->setAuthor($user);

        $this->em->persist($thread);
        $this->em->persist($comment);
        $this->em->flush();
    }

    /**
     * Update a comment
     *
     * @param Comment $comment
     */
    public function update(Comment $comment)
    {
        $comment->setUpdateAt(new \DateTime());
        $this->em->persist($comment);
        $this->em->flush();
    }
}
