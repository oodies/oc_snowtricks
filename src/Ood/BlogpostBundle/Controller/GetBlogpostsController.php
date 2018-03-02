<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogpostBundle\Controller;

use Ood\BlogpostBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class GetBlogpostsController
 *
 * @package Ood\BlogpostBundle\Controller
 */
class GetBlogpostsController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function getBlogpostsAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Post::class);
        $posts = $repository->findAll();

        return $this->render(
            '@OodBlogpost/GetBlogposts/getBlogposts.html.twig',
            [
                'posts' => $posts
            ]
        );
    }
}
