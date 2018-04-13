<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\UserBundle\Controller;

use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
/**
 * Class SecurityController
 *
 * @package Ood\UserBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils $authUtils
     *
     * @return RedirectResponse|Response
     * @throws \LogicException
     */
    public function loginAction(AuthenticationUtils $authUtils): Response
    {
        if (!is_null($this->getUser())) {
            return $this->redirectToRoute('ood_app_homepage');
        }

        $user = new User();
        $form = $this->createForm(
            LoginType::class,
            $user,
            ['action' => $this->generateUrl('ood_user_security_login')]
        );

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->render(
            '@OodUser/Security/login.html.twig',
            [
                'form'          => $form->createView(),
                'error'         => $error,
                'last_username' => $lastUsername,
                'csrf_token'    => $csrfToken
            ]
        );
    }
}
