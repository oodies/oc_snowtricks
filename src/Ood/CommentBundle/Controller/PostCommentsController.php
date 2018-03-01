<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\CommentBundle\Controller;

use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Ood\CommentBundle\Form\CommentType;
use Ood\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PostCommentsController
 *
 * @package Ood\CommentBundle\Controller
 */
class PostCommentsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response | \Symfony\Component\HttpFoundation\JsonResponse;
     * @throws \LogicException
     */
    public function postCommentsAction(Request $request)
    {
        $threadId = $request->get('threadId');

        $em = $this->getDoctrine()->getManager();

        $repositoryThread = $em->getRepository('OodCommentBundle:Thread');
        /** @var Thread $thread */
        $thread = $repositoryThread->find($threadId);

        if (is_null($thread)) {
            $thread = new Thread();
            $thread->setIdThread($threadId);
            $em->persist($thread);
            $em->flush();
        }

        $comment = new Comment();
        $comment->setThread($thread);

        $form = $this->createForm(
            CommentType::class,
            $comment,
            [
                'action' => $this->generateUrl(
                    'ood_comment_postComments_postComments',
                    ['threadId' => $thread->getIdThread()]
                ),
                'method' => 'POST'
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $comment->setAuthor($user);

            $em->persist($comment);
            $em->flush();

            $response = [
                'comment' => [
                    'body'     => $comment->getBody(),
                    'updateAt' => $comment->getUpdateAt(),
                    'username' => $comment->getAuthor()->getUsername()
                ]
            ];

            return new JsonResponse($response, Response::HTTP_CREATED);
        }

        return $this->render(
            '@OodComment/Management/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
