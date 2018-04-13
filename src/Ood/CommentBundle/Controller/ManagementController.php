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
use Ood\CommentBundle\Manager\CommentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param CommentManager $commentManager
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function listAction(CommentManager $commentManager): Response
    {
        $comments = $commentManager->findAll();

        return $this->render('@OodComment/Management/list.html.twig', ['comments' => $comments]);
    }

    /**
     * Approve a comment
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Comment        $comment
     * @param CommentManager $commentManager
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function approveAction(Comment $comment, CommentManager $commentManager): RedirectResponse
    {
        $commentManager->approve($comment);

        return $this->redirectToRoute('ood_comment_management_list');
    }

    /**
     * Disapprove a comment
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Comment        $comment
     * @param CommentManager $commentManager
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function disapproveAction(Comment $comment, CommentManager $commentManager): RedirectResponse
    {
        $commentManager->disapprove($comment);

        return $this->redirectToRoute('ood_comment_management_list');
    }

    /**
     * Change a text comment
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request        $request
     * @param Comment        $comment
     * @param CommentManager $commentManager
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @throws \LogicException
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Comment $comment, CommentManager $commentManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentManager->update($comment);

            return $this->redirectToRoute('ood_comment_management_list');
        }

        return $this->render('@OodComment/Management/edit.html.twig', ['form' => $form->createView()]);
    }
}
