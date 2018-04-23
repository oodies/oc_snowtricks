<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogBundle\Controller;

use Ood\AppBundle\Services\Paginate\PagerfantaMeta;
use Ood\BlogBundle\Entity\Category;
use Ood\BlogBundle\Entity\Post;
use Ood\BlogBundle\Form\PostType;
use Ood\BlogBundle\Manager\PostManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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
    const MAX_PER_PAGE = 15;

    /* ********************************
     *  METHODS
     */

    /**
     * Create a post
     *
     * @param Request     $request
     * @param PostManager $postManager
     *
     * @Security("has_role('ROLE_BLOGGER')")
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function newAction(Request $request, PostManager $postManager): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $postManager->add($post);

            return $this->redirectToRoute('ood_blog_post_list');
        }

        return $this->render('@OodBlog/Post/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Display a blog post
     *
     * @param string      $uniqueID
     * @param PostManager $postManager
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     */
    public function showAction($uniqueID, PostManager $postManager): Response
    {
        $post = $postManager->getByUniqueID($uniqueID);

        return $this->render('@OodBlog/Post/show.html.twig', ['post' => $post]);
    }

    /**
     * Display blog posts list
     *
     * @param Request     $request
     * @param PostManager $postManager
     *
     * @return Response
     *
     * @throws \LogicException
     */
    public function listAction(Request $request, PostManager $postManager): Response
    {
        $currentPage = $request->get('page', 1);

        $pagerfanta = $postManager->findAllWithPaginate(self::MAX_PER_PAGE, $currentPage);
        $pagerfantaMeta = new PagerfantaMeta($pagerfanta);

        $assign = [
            'posts'     => $pagerfanta->getCurrentPageResults(),
            'paginator' => $pagerfantaMeta->getMetas(),
            'vURL'      => $this->generateUrl('ood_blog_post_list')
        ];

        if ($request->isXmlHttpRequest()) {
            $template = 'OodBlogBundle:Post:list_content.html.twig';
        } else {
            $template = 'OodBlogBundle:Post:list.html.twig';
        }

        return $this->render($template, $assign);
    }

    /**
     * Display blog posts list for a category
     *
     * @param Request     $request
     * @param Category    $category
     * @param PostManager $postManager
     *
     * @ParamConverter("category", options={"mapping": {"slug":"slug"}})
     *
     * @return Response
     *
     * @throws \LogicException
     * @throws \Pagerfanta\Exception\LogicException
     */
    public function categoryAction(Request $request, Category $category, PostManager $postManager): Response
    {
        $currentPage = $request->get('page', 1);

        $pagerfanta = $postManager->findAllByCategoryWithPaginate($category, self::MAX_PER_PAGE, $currentPage);
        $pagerfantaMeta = new PagerfantaMeta($pagerfanta);

        $assign = [
            'category'  => $category,
            'posts'     => $pagerfanta->getCurrentPageResults(),
            'paginator' => $pagerfantaMeta->getMetas(),
            'vURL'      => $this->generateUrl('ood_blog_post_category', ['slug' => $category->getSlug()])
        ];

        if ($request->isXmlHttpRequest()) {
            $template = 'OodBlogBundle:Post:list_content.html.twig';
        } else {
            $template = 'OodBlogBundle:Post:category.html.twig';
        }

        return $this->render($template, $assign);
    }

    /**
     * Edit a blog post
     *
     * @Security("has_role('ROLE_BLOGGER')")
     *
     * @param Request     $request
     * @param Post        $post
     * @param PostManager $postManager
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function editAction(Request $request, Post $post, PostManager $postManager): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $postManager->update($post);

            $this->addFlash('notice', 'post.msg.saved_done');
        }

        return $this->render('@OodBlog/Post/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Remove a blog post + Thread of comments + Images + videos
     *
     * @Security("has_role('ROLE_BLOGGER')")
     *
     * @param Request     $request
     * @param Post        $post
     * @param PostManager $postManager
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     *
     * @return Response
     */
    public function removeAction(Request $request, Post $post, PostManager $postManager): Response
    {
        $postManager->remove($post);

        if ($request->isXmlHttpRequest()) {
            // From blogpost list
            $response = new Response();
            $response->setStatusCode(Response::HTTP_OK);

            return $response;
        } else {
            // From blogpost himself
            return $this->redirectToRoute('ood_blog_post_list');
        }
    }
}
