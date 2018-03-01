<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\CommentBundle\Controller;

use Ood\BlogpostBundle\Entity\Post;
use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Ood\CommentBundle\Form\CommentType;
use Ood\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @param Post    $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @return \Symfony\Component\HttpFoundation\Response | \Symfony\Component\HttpFoundation\JsonResponse;
     * @throws \LogicException
     */
    public function postCommentsAction(Request $request, Post $post)
    {
        $comment = new Comment();
        $form = $this->createForm(
            CommentType::class,
            $comment,
            [
                'action' => $this->generateUrl(
                    'ood_comment_postComments_postComments',
                    ['postId' => $post->getIdPost()]
                ),
                'method' => 'POST'
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var User $user */
            $user = $this->getUser();
            $comment->setAuthor($user);

            $repositoryThread = $em->getRepository('OodCommentBundle:Thread');
            /** @var Thread $thread */
            $thread = $repositoryThread->findOneBy(['post' => $post]);

            if (is_null($thread)) {
                $thread = new Thread();
                $thread->setPost($post);
                $em->persist($thread);
            }
            $comment->setThread($thread);

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
