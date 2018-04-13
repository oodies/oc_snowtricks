<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\UserBundle\Controller;

use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Form\UserType;
use Ood\UserBundle\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class ManagementController
 *
 * @package Ood\UserBundle\Controller
 */
class ManagementController extends Controller
{
    /**
     * Show all users
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param UserManager $userManager
     *
     * @return Response
     * @throws \LogicException
     */
    public function listAction(UserManager $userManager): Response
    {
        $users = $userManager->findAll();

        return $this->render('@OodUser/Management/list.html.twig', ['users' => $users]);
    }

    /**
     * Edit an user
     *
     * @param Request     $request
     * @param User        $user
     * @param UserManager $userManager
     *
     * @return RedirectResponse|Response
     * @throws \LogicException
     * @ParamConverter("user",
     *                  options={"id"="id"} )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     */
    public function editAction(Request $request, User $user, UserManager $userManager): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->update($user);

            if ($request->isXmlHttpRequest()) {
                $this->json(['user' => $user]);
            } else {
                return $this->redirectToRoute('ood_user_management_list');
            }
        }

        if ($request->isXmlHttpRequest()) {
            $template = '@OodUser/Management/edit_content.html.twig';
        } else {
            $template = '@OodUser/Management/edit.html.twig';
        }

        return $this->render($template, ['form' => $form->createView()]);
    }

    /**
     * Lock an user
     *
     * @param User        $user
     * @param UserManager $userManager
     *
     * @ParamConverter("user",
     *                  options={"id"="id"} )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return RedirectResponse
     */
    public function lockAction(User $user, UserManager $userManager): RedirectResponse
    {
        $userManager->lock($user);

        return $this->redirectToRoute('ood_user_management_list');
    }

    /**
     * Unlock an user
     *
     * @param User        $user
     * @param UserManager $userManager
     *
     * @ParamConverter("user",
     *                  options={"id"="id"} )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return RedirectResponse
     */
    public function unlockAction(User $user, UserManager $userManager): RedirectResponse
    {
        $userManager->unlock($user);

        return $this->redirectToRoute('ood_user_management_list');
    }
}
