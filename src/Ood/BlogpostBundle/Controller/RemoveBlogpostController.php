<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\BlogpostBundle\Controller;

use Ood\CommentBundle\Entity\Comment;
use Ood\BlogpostBundle\Entity\Post;
use Ood\CommentBundle\Entity\Thread;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RemoveBlogpostController
 *
 * @package Ood\BlogpostBundle\Controller
 */
class RemoveBlogpostController extends Controller
{

    /**
     * @param Post $post
     *
     * @ParamConverter("post", options={"id"="postId"})
     *
     * @return Response
     */
    public function removeBlogpostAction (Post $post) {

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
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_NO_CONTENT);

        return $response;
    }
}
