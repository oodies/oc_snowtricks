<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\CommentBundle\Controller;

use Ood\BlogpostBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class getCommentsController
 *
 * @package Ood\CommentBundle\Controller
 */
class GetCommentsController extends Controller
{
    /**
     * @param Post $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function getCommentsAction(Post $post)
    {
        $repositoryThread = $this->getDoctrine()->getManager()->getRepository('OodCommentBundle:Thread');
        $thread = $repositoryThread->findOneBy(['post' => $post]);

        $repositoryComment = $this->getDoctrine()->getManager()->getRepository('OodCommentBundle:Comment');
        $comments = $repositoryComment->findBy(['thread' => $thread]);

        return $this->render(
            '@OodComment/GetComments/getComments.html.twig',
            [
                'thread'   => $thread,
                'comments' => $comments
            ]
        );
    }
}
