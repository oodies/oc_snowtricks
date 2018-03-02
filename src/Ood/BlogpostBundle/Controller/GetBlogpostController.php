<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogpostBundle\Controller;

use Ood\BlogpostBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class GetBlogpostController
 *
 * @package Ood\BlogpostBundle\Controller
 */
class GetBlogpostController extends Controller
{
    /**
     * @param Post $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function getBlogpostAction(Post $post)
    {
        return $this->render(
            '@OodBlogpost/GetBlogpost/getBlogpost.html.twig',
            ['post' => $post]
        );
    }
}
