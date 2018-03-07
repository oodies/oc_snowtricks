<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\CommentBundle\Controller;

use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Ood\CommentBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ThreadCommentController
 *
 * @package Ood\CommentBundle\Controller
 */
class ThreadCommentController extends Controller
{
    /**
     * View of a comments thread
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function threadAction(Request $request)
    {
        $idThread = $request->get('threadId');
        $em = $this->getDoctrine()->getManager();

        $repositoryThread = $em->getRepository('OodCommentBundle:Thread');
        $thread = $repositoryThread->find($idThread);

        $comments = $em->getRepository('OodCommentBundle:Comment')->findBy(
            ['thread' => $thread]
        );

        return $this->render(
            '@OodComment/ThreadComment/thread.html.twig',
            [
                'thread'   => $thread,
                'comments' => $comments
            ]
        );
    }

    /**
     * Add a new comment
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response | \Symfony\Component\HttpFoundation\JsonResponse;
     * @throws \LogicException
     */
    public function newCommentAction(Request $request)
    {
        $threadId = $request->get('threadId');

        $comment = new Comment();
        $form = $this->createForm(
            CommentType::class, $comment,
            [
                'action' => $this->generateUrl(
                    'ood_comment_threadComment_newComment',
                    ['threadId' => $threadId]
                ),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Thread $thread */
            $thread = $em->getRepository('OodCommentBundle:Thread')->find($threadId);

            if (is_null($thread)) {
                $thread = new Thread();
                $thread->setIdThread($threadId);
                $em->persist($thread);
            }
            $comment->setThread($thread)
                    ->setAuthor($this->getUser());
            $em->persist($comment);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                $response = [
                    'comment' => [
                        'body'     => $comment->getBody(),
                        'updateAt' => $comment->getUpdateAt(),
                        'username' => $comment->getAuthor()->getUsername()
                    ]
                ];
                return new JsonResponse($response, Response::HTTP_CREATED);
            } else {
                $this->redirect($request->getRequestUri());
            }
        }

        return $this->render(
            '@OodComment/ThreadComment/form.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
