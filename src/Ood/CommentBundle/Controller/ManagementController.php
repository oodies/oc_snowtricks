<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\CommentBundle\Controller;

use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

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
     * @Security("has_role('ROLE_ADMIN')")
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function approveAction(Comment $comment)
    {
        $comment
            ->setEnabled(true)
            ->setUpdateAt(new \DateTime());

        $thread = $comment->getThread();
        $thread->commentCounter();

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->persist($thread);
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
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function disapproveAction(Comment $comment)
    {
        $comment
            ->setEnabled(false)
            ->setUpdateAt(new \DateTime());

        $thread = $comment->getThread();
        $thread->commentCounter(-1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->persist($thread);
        $em->flush();

        return $this->redirectToRoute('ood_comment_management_list');
    }

    /**
     * Change a text comment
     *
     * @param Request $request
     * @param Comment $comment
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Comment $comment)
    {
        $form = $this->createForm(
            CommentType::class,
            $comment,
            [
                'action' => $this->generateUrl(
                    'ood_comment_management_edit',
                    ['commentId' => $comment->getIdComment()]
                ),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUpdateAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('ood_comment_management_list');
        }

        return $this->render(
            '@OodComment/Management/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
