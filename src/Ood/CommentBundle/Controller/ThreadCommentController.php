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
use Symfony\Component\HttpFoundation\Response;
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
        if ($request->getMethod() === 'POST') {
            $page = $request->get('page');
        } else {
            $page = 0;
        }

        // Get param request
        $params = [];
        $params['page'] = $page;
        $params['limit'] = $request->get('limit', self::ITEMS_PER_PAGE);
        $params['offset'] = ((int)$page) * ((int)$params['limit']);

        $idThread = $request->get('threadId');
        $em = $this->getDoctrine()->getManager();

        $repositoryThread = $em->getRepository('OodCommentBundle:Thread');
        /** @var \Ood\CommentBundle\Entity\Thread $thread */
        $thread = $repositoryThread->find($idThread);

        /** @var \Ood\CommentBundle\Repository\CommentRepository $repository */
        $repository = $em->getRepository(Comment::Class);
        $comments = $repository->findByThread($thread, $params);
        $totalComments = $repository->getNbCommentsByThread($thread);

        /** @var integer $restOfComments Number of comments remaining to be displayed */
        $restOfComments = $totalComments - ($page + 1) * self::ITEMS_PER_PAGE;
        /** @var integer $numberItemNext Next number of post to display */
        $numberItemNext = ($restOfComments > self::ITEMS_PER_PAGE) ? self::ITEMS_PER_PAGE : $restOfComments;

        $assign = [
            'thread'         => $thread,
            'comments'       => $comments,
            'page'           => $page,
            'totalComments'  => $totalComments,
            'restOfComments' => $restOfComments,
            'vURL'           => $this->generateUrl('ood_comment_threadComment_thread', ['threadId' => $idThread]),
            'numberItemNext' => $numberItemNext,
            'itemsPerPage'   => self::ITEMS_PER_PAGE
        ];

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
                $em->persist($thread);
            }
            $comment->setThread($thread)
                    ->setAuthor($user);
            $em->persist($comment);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                $response = [
                    'comment' => [
                        'idComment' => $comment->getIdComment(),
                        'body'      => $comment->getBody(),
                        'updateAt'  => $comment->getUpdateAt()->format('d/m/Y h:i'),
                        'username'  => $comment->getAuthor()->getUsername()
                    ]
                ];
                return new JsonResponse($response, Response::HTTP_CREATED);
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
