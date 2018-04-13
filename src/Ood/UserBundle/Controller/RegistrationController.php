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
use Ood\UserBundle\Manager\UserManager;
use Ood\UserBundle\Services\Messaging;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RegistrationController
 *
 * @package Ood\UserBundle\Controller
 */
class RegistrationController extends Controller
{
    /**
     * View the registration form for creating a new account
     *
     * @param Request     $request
     * @param UserManager $userManager
     * @param Messaging   $messaging
     *
     * @return RedirectResponse|Response
     * @throws \LogicException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function registerAction(Request $request, UserManager $userManager, Messaging $messaging): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->register($user);
            $messaging->confirmationRegistrationRequest($user);

            $this->get('session')->set('ood_user_registration/email', $user->getEmail());
            // Forward next step
            return $this->redirectToRoute('ood_user_registration_check_email');
        }

        return $this->render('@OodUser/Registration/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Tell the user to check their email provider
     *
     * @param UserManager $userManager
     *
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     *
     * @return RedirectResponse|Response
     */
    public function checkEmailAction(UserManager $userManager): Response
    {
        $email = $this->get('session')->get('ood_user_registration/email');

        if (empty($email)) {
            return $this->redirectToRoute('ood_user_registration_register');
        }

        $this->get('session')->remove('ood_user_registration/email');
        $user = $userManager->loadUserByUsername($email);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $email));
        }

        return $this->render(
            'OodUserBundle:Registration:check_email.html.twig', ['user' => $user, 'step' => 1]
        );
    }

    /**
     * Receive the confirmation token from user email provider, login the user.
     *
     * @param string      $token
     * @param UserManager $userManager
     *
     * @throws NotFoundHttpException*
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return RedirectResponse
     */
    public function confirmAction(string $token, UserManager $userManager): RedirectResponse
    {
        $user = $userManager->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }

        $userManager->confirm($user);

        // auto authenticate
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));

        // Forward next step
        return $this->redirectToRoute('ood_user_registration_confirmed');
    }


    /**
     * Tell the user his account is now confirmed
     *
     * @throws AccessDeniedException
     * @throws \LogicException
     *
     * @return Response
     */
    public function confirmedAction(): Response
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render(
            'OodUserBundle:Registration:confirmed.html.twig', ['user' => $user, 'step' => 2]
        );
    }
}
