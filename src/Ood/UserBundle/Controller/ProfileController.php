<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\UserBundle\Controller;

use Ood\UserBundle\Form\ProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ProfileController
 *
 * @package Ood\UserBundle\Controller
 */
class ProfileController extends Controller
{
    /**
     * @param Request       $request
     * @param UserInterface $user
     *
     * @Security("has_role('ROLE_AUTHOR')")
     *
     * @throws \LogicException
     *
     * @return Response
     */
    public function editAction(Request $request, UserInterface $user): Response
    {
        $form = $this->createForm(
            ProfileType::class, $user
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'profile.msg.saved_done');
        }

        return $this->render(
            '@OodUser/Profile/edit.html.twig',
            ['form' => $form->createView()]
        );
    }
}
