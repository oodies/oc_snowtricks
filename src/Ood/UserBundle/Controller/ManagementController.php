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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {

        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $users = $repository->findAll();

        return $this->render('@OodUser/Management/list.html.twig', ['users' => $users]);
    }

    /**
     * Edit an user
     *
     * @param Request $resquest
     * @param User    $user
     *
     * @ParamConverter("user",
     *                  options={"id"="id"} )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdateAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

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
     * @param User    $user
     *
     * @ParamConverter("user",
     *                  options={"id"="id"} )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function lockAction(User $user)
    {
        $user->setUpdateAt(new \DateTime())
             ->setLocked(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('ood_user_management_list');
    }

    /**
     * Unlock an user
     *
     * @param User    $user
     *
     * @ParamConverter("user",
     *                  options={"id"="id"} )
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unlockAction(User $user)
    {
        $user->setUpdateAt(new \DateTime())
             ->setLocked(false);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('ood_user_management_list');
    }
}
