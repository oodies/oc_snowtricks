<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace Ood\BlogBundle\Controller;

use Ood\BlogBundle\Entity\Category;
use Ood\BlogBundle\Form\CategoryType;
use Ood\BlogBundle\Manager\CategoryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 *
 * @package Ood\BlogBundle\Controller
 */
class CategoryController extends Controller
{
    /**
     * Create a category
     *
     * @Security("has_role('ROLE_BLOGGER')")
     *
     * @param Request         $request
     * @param CategoryManager $categoryManager
     *
     * @return Response
     *
     * @throws \LogicException
     */
    public function newAction(Request $request, CategoryManager $categoryManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryManager->add($category);

            return $this->redirectToRoute('ood_blog_category_list');
        }

        return $this->render('@OodBlog/Category/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit a category
     *
     * @Security("has_role('ROLE_BLOGGER')")
     *
     * @param Request         $request
     * @param Category        $category
     * @param CategoryManager $categoryManager
     *
     * @ParamConverter("category", options={"mapping":{"slug":"slug"}} )
     *
     * @return Response
     *
     * @throws \LogicException
     */
    public function editAction(Request $request, Category $category, CategoryManager $categoryManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryManager->edit($category);

            $this->addFlash('notice', 'category.msg.saved_done');
        }

        return $this->render('@OodBlog/Category/edit.html.twig', ['form' => $form->createView()]);
    }


    /**
     * Display a categories list
     *
     * @param CategoryManager $categoryManager
     *
     * @return Response
     *
     * @throws \LogicException
     */
    public function listAction(CategoryManager $categoryManager): Response
    {
        $categories = $categoryManager->findAll();

        return $this->render('@OodBlog/Category/list.html.twig', ['categories' => $categories]);
    }
}
