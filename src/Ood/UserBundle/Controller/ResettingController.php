<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\UserBundle\Controller;

use Ood\UserBundle\Entity\User;
use Ood\UserBundle\Form\ResettingType;
use Ood\UserBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @return Response
     */
    public function requestAction()
    {
        return $this->render('@OodUser/Resetting/request.html.twig', ['step' => 1]);
    }

    /**
     * Request reset user password: submit form and send email.
     *
     * @param Request $request
     */
    public function sendEmailAction(Request $request)
    {
        $username = $request->get('username');

        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repository */
        $repository = $em->getRepository(User::class);
        /** @var User $user */
        $user = $repository->loadUserByUsername($username);
        $token = rtrim(strtr(base64_encode(random_bytes(20)), '+/', '-_'), '=');
        $user->setConfirmationToken($token);
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
     * @return RedirectResponse
     */
    public function checkEmailAction(Request $request)
    {
        $username = $request->get('username');

        if (empty($username)) {
            // the user does not come from the sendEmail action
            return new RedirectResponse($this->generateUrl('ood_user_resetting_request'));
        }

        return $this->render('OodUserBundle:Resetting:check_email.html.twig',['step' => 2]);
    }


    /**
     * Reset user password.
     *
     * @param Request $request
     */
    public function resetAction(Request $request, string $token)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var UserRepository $repository */
        $repository = $em->getRepository(User::class);
        /** @var User $user */
        $user = $repository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }
        $form = $this->createForm(ResettingType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                password_hash(
                    $user->getPlainPassword()
                    , PASSWORD_BCRYPT, [
                        'cost' => 13
                    ]
                )
            );
            $user->setPlainPassword(null);
            $user->setConfirmationToken(null);

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
