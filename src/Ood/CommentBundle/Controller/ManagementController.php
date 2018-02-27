<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\CommentBundle\Controller;

use Ood\CommentBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class ManagementController
 *
 * @package Ood\CommentBundle\Controller
 */
class ManagementController extends Controller
{
    /**
     * Show all comments
     */
    public function listAction()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Comment::class);
        $comments = $repository->findAll();

        return $this->render('@OodComment/Management/list.html.twig', ['comments' => $comments]);
    }
}
