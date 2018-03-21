<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\CommentBundle\Controller;

use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Entity\Thread;
use Ood\CommentBundle\Form\CommentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ThreadCommentController
 *
 * @package Ood\CommentBundle\Controller
 */
class ThreadCommentController extends Controller
{
    /* ********************************
     *  CONSTANTS
     */

    /** Maximum number of results from index */
    const ITEMS_PER_PAGE = 10;

    /**
     * View of a comments thread
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function threadAction(Request $request)
    {
        $idThread = $request->get('threadId');

        if ($request->getMethod() === 'POST') {
            $page = $request->get('page');
        } else {
            $page = 0;
        }

        // Params for paginator
        $params = [];
        $params['page'] = $page;
        $params['limit'] = $request->get('limit', self::ITEMS_PER_PAGE);
        $params['offset'] = ((int)$page) * ((int)$params['limit']);

        // default assignment
        $assign = [];
        $assign['thread'] = null;
        $assign['page'] = $page;
        $assign['vURL'] = $this->generateUrl('ood_comment_threadComment_thread', ['threadId' => $idThread]);
        $assign['comments'] = [];
        $assign['totalComments'] = 0;
        $assign['restOfComments'] = 0;
        $assign['numberItemNext'] = 0;
        $assign['itemsPerPage'] = self::ITEMS_PER_PAGE;

        // Get manager
        $em = $this->getDoctrine()->getManager();

        /** @var \Ood\CommentBundle\Entity\Thread $thread */
        $thread = $em->getRepository('OodCommentBundle:Thread')->find($idThread);

        if ($thread !== null) {
            /** @var \Ood\CommentBundle\Repository\CommentRepository $repository */
            $repository = $em->getRepository(Comment::Class);
            $comments = $repository->findByThread($thread, $params);
            $totalComments = $repository->getNbCommentsByThread($thread);
            /** @var integer $restOfComments Number of comments remaining to be displayed */
            $restOfComments = $totalComments - ($page + 1) * self::ITEMS_PER_PAGE;
            /** @var integer $numberItemNext Next number of post to display */
            $numberItemNext = ($restOfComments > self::ITEMS_PER_PAGE) ? self::ITEMS_PER_PAGE : $restOfComments;

            $assign['thread'] = $thread;
            $assign['comments'] = $comments;
            $assign['totalComments'] = $totalComments;
            $assign['restOfComments'] = $restOfComments;
            $assign['numberItemNext'] = $numberItemNext;
        }

        if ($request->isXmlHttpRequest()) {
            $template = 'OodCommentBundle:ThreadComment:list_content.html.twig';
        } else {
            $template = 'OodCommentBundle:ThreadComment:list.html.twig';
        }

        return $this->render($template, $assign);
    }

    /**
     * Add a new comment
     *
     * @param Request       $request
     * @param UserInterface $user
     *
     * @Security("has_role('ROLE_AUTHOR')")
     *
     * @return \Symfony\Component\HttpFoundation\Response | \Symfony\Component\HttpFoundation\JsonResponse;
     * @throws \LogicException
     */
    public function newCommentAction(Request $request)
    {
        $threadId = $request->get('threadId');

        $user = $this->getUser();

        $comment = new Comment();
        $form = $this->createForm(
            CommentType::class, $comment,
            [
                'action' => $this->generateUrl(
                    'ood_comment_threadComment_newComment',
                    ['threadId' => $threadId]
                ),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var Thread $thread */
            $thread = $em->getRepository('OodCommentBundle:Thread')->find($threadId);

            if (is_null($thread)) {
                $thread = new Thread();
                $thread->setIdThread($threadId);
            }
            $thread->commentCounter(); // increment comment counter
            $comment->setThread($thread)
                    ->setAuthor($user);

            $em->persist($thread);
            $em->persist($comment);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->render(
                    '@OodComment/ThreadComment/comment.html.twig',
                    ['comment' => $comment]
                );
            } else {
                return $this->redirect($request->getRequestUri());
            }
        }

        if ($request->isXmlHttpRequest()) {
            $Twig = $this->container->get('twig');
            return new JsonResponse(
                [
                    'hasError' => (bool)count($form->getErrors(true)),
                    'form'     => $Twig->render(
                        '@OodComment/ThreadComment/form.html.twig',
                        ['form' => $form->createView()]
                    )
                ]
            );
        } else {
            return $this->render(
                '@OodComment/ThreadComment/form.html.twig',
                ['form' => $form->createView()]
            );
        }
    }
}
