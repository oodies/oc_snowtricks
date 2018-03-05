<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogpostBundle\Controller;

use Ood\BlogpostBundle\Entity\Post;
use Ood\BlogpostBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostBlogpostController
 *
 * @package Ood\BlogpostBundle\Controller
 */
class PostBlogpostController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function postBlogpostAction(Request $request)
    {
        $post = new Post();
        $user = $this->getUser();
        $post->setBlogger($user);

        $form = $this->createForm(
            PostType::class,
            $post,
            [
                'action' => $this->generateUrl(
                    'ood_blogpost_postBlogpost_postBlogpost'
                ),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->redirect(
                $this->generateUrl(
                    'ood_blogpost_getBlogpost_getBlogpost',
                    ['postId' => $post->getIdPost()]
                )
            );
        }

        return $this->render(
            '@OodBlogpost/PostBlogpost/postBlogpost.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
