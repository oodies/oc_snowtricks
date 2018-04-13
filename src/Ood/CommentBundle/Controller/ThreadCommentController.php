<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\CommentBundle\Controller;

use Ood\AppBundle\Services\Paginate\PagerfantaMeta;
use Ood\CommentBundle\Entity\Comment;
use Ood\CommentBundle\Form\CommentType;
use Ood\CommentBundle\Manager\CommentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\BrowserKit\Response;
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
    const MAX_PER_PAGE = 10;

    /**
     * View of a comments thread
     *
     * @param Request $request
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function threadAction(Request $request, CommentManager $commentManager)
    {
        $idThread = $request->get('threadId');
        $currentPage = $request->get('page', 1);

        // default assignment
        $assign = [];
        $assign['vURL'] = $this->generateUrl('ood_comment_threadComment_thread', ['threadId' => $idThread]);
        $assign['thread'] = null;
        $assign['comments'] = [];

        /** @var \Ood\CommentBundle\Entity\Thread $thread */
        $thread = $this->getDoctrine()->getManager()->getRepository('OodCommentBundle:Thread')->find($idThread);

        if ($thread !== null) {
            $pagerfanta = $commentManager->findAllByThreadWithPaginate($thread, self::MAX_PER_PAGE, $currentPage);
            $pagerfantaMeta = new PagerfantaMeta($pagerfanta);

            $assign['thread'] = $thread;
            $assign['comments'] = $pagerfanta->getCurrentPageResults();
            $assign['paginator'] = $pagerfantaMeta->getMetas();
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
     * @throws \Exception
     */
    public function newCommentAction(Request $request, CommentManager $commentManager)
    {
        $threadId = $request->get('threadId');
        $user = $this->getUser();
        $comment = new Comment();

        $form = $this->createForm(
            CommentType::class, $comment,
            [
                'action' => $this->generateUrl('ood_comment_threadComment_newComment',['threadId' => $threadId]),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $commentManager->add($comment, $user, $threadId);

            if ($request->isXmlHttpRequest()) {
                return $this->render('@OodComment/ThreadComment/comment.html.twig', ['comment' => $comment]);
            } else {
                return $this->redirect($request->getRequestUri());
            }
        }

        if ($request->isXmlHttpRequest()) {
            $Twig = $this->container->get('twig');
            return new JsonResponse(
                [
                    'hasError' => (bool)count($form->getErrors(true)),
                    'form'     => $Twig->render('@OodComment/ThreadComment/form.html.twig',['form' => $form->createView()])
                ]
            );
        } else {
            return $this->render('@OodComment/ThreadComment/form.html.twig',['form' => $form->createView()]);
        }
    }
}
