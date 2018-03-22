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
use Ood\BlogBundle\Repository\PostRepository;
use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    /* ********************************
     *  CONSTANTS
     */

    /** Maximum number of results from index */
    const ITEMS_PER_PAGE = 15;

    /* ********************************
     *  METHODS
     */

    /**
     * Create a post
     *
     * @param Request       $request
     * @param UserInterface $user
     *
     * @Security("has_role('ROLE_BLOGGER')")
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

            /** @var \Ood\BlogBundle\Services\Utils $utils */
            $utils = $this->container->get('ood_blog.utils');
            $post->setUniqueID($utils->tinyUrl($post->getIdPost()));
            $em->persist($post);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('ood_blog_post_list', ['postId' => $post->getIdPost()])
            );
        }

        return $this->render('@OodBlog/Post/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Display a blog
     *
     * @param string $uniqueID
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function showAction($uniqueID)
    {
        /** @var PostRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Post::class);
        $post = $repository->getByUniqueID($uniqueID);

        return $this->render('@OodBlog/Post/show.html.twig', ['post' => $post]);
    }

    /**
     * Display posts list
     *
     * QueryParam (name="page", requirement="\d+", default="0", description="number page")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function listAction(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $page = $request->get('page');
        } else {
            $page = 0;
        }

        // Get param request
        $params = [];
        $params['page'] = $page;
        $params['limit'] = $request->get('limit', self::ITEMS_PER_PAGE);
        $params['offset'] = ((int)$page) * ((int)$params['limit']);

        /** @var PostRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Post::class);
        $posts = $repository->findResources($params);
        $totalPosts = $repository->getNbItems();

        /** @var integer $restOfPosts Number of posts remaining to be displayed */
        $restOfPosts = $totalPosts - ($page + 1) * self::ITEMS_PER_PAGE;
        /** @var integer $numberItemNext Next number of post to display */
        $numberItemNext = ($restOfPosts > self::ITEMS_PER_PAGE) ? self::ITEMS_PER_PAGE : $restOfPosts;

        $assign = [
            'posts'          => $posts,
            'page'           => $page,
            'totalPosts'     => $totalPosts,
            'restOfPosts'    => $restOfPosts,
            'vURL'           => 'ood_blog_post_list',
            'numberItemNext' => $numberItemNext,
            'itemsPerPage'   => self::ITEMS_PER_PAGE
        ];

        if ($request->isXmlHttpRequest()) {
            $template = 'OodBlogBundle:Post:list_content.html.twig';
        } else {
            $template = 'OodBlogBundle:Post:list.html.twig';
        }

        return $this->render($template, $assign);
    }

    /**
     * @param Request $request
     * @param Post    $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @Security("has_role('ROLE_BLOGGER')")
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

        return $this->render('@OodBlog/Post/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Remove a post + Thread of comments + Images + videos
     *
     * @param Request $request
     * @param Post    $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @Security("has_role('ROLE_BLOGGER')")
     *
     * @return Response
     */
    public function removeAction(Request $request, Post $post)
    {
        // TODO vérifier que les images et bien les videos se suppriment de la DB
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

            // TODO Supprimer les images en dur sur le serveur
        }

        if ($request->isXmlHttpRequest()) {
            // From blogpost list
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        } else {
            // From blogpost himself
            return $this->redirect($this->generateUrl('ood_blog_post_list'));
        }
    }
}
