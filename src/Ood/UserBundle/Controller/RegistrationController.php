<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\UserBundle\Controller;

use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class RegistrationController
 *
 * @package Ood\UserBundle\Controller
 */
class RegistrationController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setRoles(['ROLE_USER'])
                 ->setLocked(false);
            $user->setPassword(
                password_hash(
                    $user->getPlainPassword()
                    , PASSWORD_BCRYPT, [
                        'cost' => 13
                    ]
                )
            );
            $user->setPlainPassword("");

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('ood_user_security_login'));
        }

        return $this->render(
            '@OodUser/Registration/register.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
