<?php

namespace Ood\CommentBundle\Handler;

use Ood\CommentBundle\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommentHandler
 *
 * @package Ood\CommentBundle\Handler
 */
class CommentHandler
{

    /** @var EntityManagerInterface $em */
    protected $em;

    /**
     * CommentHandler constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Approve a comment
     *
     * @param FormInterface $form
     * @param Comment       $comment
     */
    public function approve(Comment $comment)
    {
        $comment
            ->setEnabled(true)
            ->setUpdateAt(new \DateTime());

        /** @var \Ood\CommentBundle\Entity\Thread $thread */
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
     * Change a comment
     *
     * @param Request       $request
     * @param FormInterface $form
     * @param Comment       $comment
     *
     * @return bool
     */
    public function change(Request $request, FormInterface $form, Comment $comment)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUpdateAt(new \DateTime());

            $this->em->persist($comment);
            $this->em->flush();

            return true;
        }
        return false;
    }
}
