<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\CommentBundle\Controller;

use Ood\CommentBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class ManagementController
 *
 * @package Ood\CommentBundle\Controller
 */
class ManagementController extends Controller
{
    /**
     * Show all comments
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Comment::class);
        $comments = $repository->findAll();

        return $this->render('@OodComment/Management/list.html.twig', ['comments' => $comments]);
    }

    /**
     * Approve a comment
     *
     * @param Comment $comment
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveAction(Comment $comment)
    {
        $comment
            ->setEnabled(true)
            ->setUpdateAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('ood_comment_management_list');
    }

    /**
     * Disapprove a comment
     *
     * @param Comment $comment
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function disapproveAction(Comment $comment)
    {
        $comment
            ->setEnabled(false)
            ->setUpdateAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('ood_comment_management_list');
    }
}
