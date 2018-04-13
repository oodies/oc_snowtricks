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
use Ood\UserBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @param Request $request
     *
     * @throws \LogicException
     *
     * @return RedirectResponse|Response
     */
    public function requestAction(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(
            ResettingRequestType::class,
            $user, [
                'action' => $this->generateUrl('ood_user_resetting_request'),
                'method' => 'POST'
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('ood_user_resetting_send_email', ['username' => $user->getUsername()]);
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
     * @param Request     $request
     *
     * @param UserManager $userManager
     *
     * @return RedirectResponse
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendEmailAction(Request $request, UserManager $userManager): RedirectResponse
    {
        $username = $request->get('username');

        $user = $userManager->loadUserByUsername($username);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with username "%s" does not exist', $username)
            );
        }

        $userManager->confirmationToken($user);

        /** @var \Ood\UserBundle\Services\Messaging $messaging */
        $messaging = $this->container->get('ood_user.resetting.messaging');
        $messaging->passwordResettingRequest($user);

        // Forward next step
        return $this->redirectToRoute('ood_user_resetting_check_email', ['username' => $username]);
    }

    /**
     * Tell the user to check his email provider.
     *
     * @param Request $request
     *
     * @throws \LogicException
     *
     * @return RedirectResponse|Response
     */
    public function checkEmailAction(Request $request): Response
    {
        $username = $request->get('username');

        if (empty($username)) {
            // the user does not come from the sendEmail action
            return $this->redirectToRoute('ood_user_resetting_request');
        }

        return $this->render('OodUserBundle:Resetting:check_email.html.twig', ['step' => 2]);
    }


    /**
     * Reset user password.
     *
     * @param Request     $request
     * @param string      $token
     * @param UserManager $userManager
     *
     * @return RedirectResponse|Response
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     */
    public function resetAction(Request $request, string $token, UserManager $userManager): Response
    {
        $user = $userManager->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }
        $form = $this->createForm(ResettingResetType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->changePassword($user);

            return $this->redirectToRoute('ood_user_security_login');
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
