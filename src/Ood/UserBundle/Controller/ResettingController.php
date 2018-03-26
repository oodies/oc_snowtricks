<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\UserBundle\Controller;

use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Form\ResettingRequestType;
use Ood\UserBundle\Form\ResettingResetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class ResettingController
 *
 * @package Ood\UserBundle\Controller
 */
class ResettingController extends Controller
{

    /**
     * Request reset user password: show form
     *
     * @param Request $request
     *
     * @throws \LogicException
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function requestAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(
            ResettingRequestType::class, $user, [
            'action' => $this->generateUrl('ood_user_resetting_request'),
            'method' => 'POST'
        ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Forward next step
            return new RedirectResponse(
                $this->generateUrl('ood_user_resetting_send_email', ['username' => $user->getUsername()])
            );
        }

        return $this->render(
            '@OodUser/Resetting/request.html.twig',
            [
                'form' => $form->createView(),
                'step' => 1
            ]
        );
    }

    /**
     * Request reset user password: submit form and send email.
     *
     * @param Request $request
     *
     * @throws NotFoundHttpException
     *
     * @return RedirectResponse
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->get('username');

        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository(User::class)->loadUserByUsername($username);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with username "%s" does not exist', $username)
            );
        }

        $user->setConfirmationToken(rtrim(strtr(base64_encode(random_bytes(20)), '+/', '-_'), '='));
        $em->persist($user);
        $em->flush();

        /** @var \Ood\UserBundle\Services\Messaging $messaging */
        $messaging = $this->container->get('ood_user.resetting.messaging');
        $messaging->passwordResettingRequest($user);

        // Forward next step
        return new RedirectResponse($this->generateUrl('ood_user_resetting_check_email', ['username' => $username]));
    }

    /**
     * Tell the user to check his email provider.
     *
     * @param Request $request
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function checkEmailAction(Request $request)
    {
        $username = $request->get('username');

        if (empty($username)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('ood_user_resetting_request'));
        }

        return $this->render('OodUserBundle:Resetting:check_email.html.twig', ['step' => 2]);
    }


    /**
     * Reset user password.
     *
     * @param Request                      $request
     * @param string                       $token
     * @param UserPasswordEncoderInterface $encoder
     *
     * @throws NotFoundHttpException
     * @throws \LogicException
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetAction(Request $request, string $token, UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User $user */
        $user = $em->getRepository(User::class)->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }
        $form = $this->createForm(ResettingResetType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $user->getPlainPassword()))
                 ->setPlainPassword(null)
                 ->setConfirmationToken(null);

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('ood_user_security_login'));
        }

        return $this->render(
            'OodUserBundle:Resetting:reset.html.twig',
            [
                'token' => $token,
                'form'  => $form->createView(),
                'step'  => 3
            ]
        );
    }
}
