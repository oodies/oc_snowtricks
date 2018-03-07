<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogBundle\Controller;

use Ood\BlogBundle\Entity\Post;
use Ood\BlogBundle\Form\PostType;
use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PostController
 *
 * @package Ood\BlogBundle\Controller
 */
class PostController extends Controller
{
    /**
     * Create a post
     *
     * @param Request       $request
     * @param UserInterface $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function newAction(Request $request, UserInterface $user)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post->setBlogger($user));
            $em->flush();

            return $this->redirect(
                $this->generateUrl('ood_blog_post_list', ['postId' => $post->getIdPost()])
            );
        }

        return $this->render(
            '@OodBlog/Post/new.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Display a blog
     *
     * @param Post $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function showAction(Post $post)
    {
        return $this->render('@OodBlog/Post/show.html.twig', ['post' => $post]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function listAction()
    {
        return $this->render(
            '@OodBlog/Post/list.html.twig',
            [
                'posts' => $this->getDoctrine()->getManager()->getRepository(Post::class)->findAll()
            ]
        );
    }

    /**
     * @param Request $request
     * @param Post    $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @throws \LogicException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response*
     */
    public function editAction(Request $request, Post $post)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            // TODO FlashBag
        }

        return $this->render(
            '@OodBlog/Post/edit.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @param Post $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @return Response
     */
    public function removeAction(Post $post)
    {

        $em = $this->getDoctrine()->getManager();

        if ($post) {
            $repositoryThread = $em->getRepository(Thread::class);
            $thread = $repositoryThread->find($post->getIdPost());

            if ($thread) {
                $repositoryComment = $em->getRepository(Comment::class);
                $comments = $repositoryComment->findBy(['thread' => $thread]);

                foreach ($comments as $comment) {
                    $em->remove($comment);
                }
                $em->remove($thread);
            }

            $em->remove($post);
            $em->flush();
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }

}
