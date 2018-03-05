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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PutBlogpostController
 *
 * @package Ood\BlogpostBundle\Controller
 */
class PutBlogpostController extends Controller
{
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
    public function putBlogpostAction(Request $request, Post $post)
    {
        $form = $this->createForm(
            PostType::class,
            $post,
            [
                'action' => $this->generateUrl(
                    'ood_blogpost_putBlogpost_putBlogpost',
                    [
                        'postId' => $post->getIdPost()
                    ]
                ),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect(
                $this->generateUrl(
                    'ood_blogpost_getBlogpost_getBlogpost',
                    ['postId' => $post->getIdPost()]
                )
            );
        }

        return $this->render(
            '@OodBlogpost/PutBlogpost/putBlogpost.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
