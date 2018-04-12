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
use Ood\CommentBundle\Handler\CommentHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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
     * @throws \LogicException
     *
     * @return Response
     */
    public function listAction(): Response
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Comment::class);
        $comments = $repository->findAll();

        return $this->render('@OodComment/Management/list.html.twig', ['comments' => $comments]);
    }

    /**
     * Approve a comment
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Comment        $comment
     * @param CommentHandler $handler
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @return RedirectResponse
     */
    public function approveAction(Comment $comment, CommentHandler $handler): RedirectResponse
    {
        $handler->approve($comment);

        return $this->redirectToRoute('ood_comment_management_list');
    }

    /**
     * Disapprove a comment
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Comment        $comment
     * @param CommentHandler $handler
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function disapproveAction(Comment $comment, CommentHandler $handler): RedirectResponse
    {
        $handler->disapprove($comment);

        return $this->redirectToRoute('ood_comment_management_list');
    }

    /**
     * Change a text comment
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request        $request
     * @param Comment        $comment
     * @param CommentHandler $handler
     *
     * @ParamConverter("comment",
     *                  options={"id"="commentId"})
     *
     * @throws \LogicException
     *
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, Comment $comment, CommentHandler $handler): Response
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

        if ($handler->change($request, $form, $comment)) {
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
