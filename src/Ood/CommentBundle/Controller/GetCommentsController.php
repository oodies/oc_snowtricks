<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class getCommentsController
 *
 * @package Ood\CommentBundle\Controller
 */
class GetCommentsController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function getCommentsAction(Request $request)
    {
        $idThread = $request->get('threadId');

        $repositoryThread = $this->getDoctrine()->getManager()->getRepository('OodCommentBundle:Thread');
        $thread = $repositoryThread->find($idThread);

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
