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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     *
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return RedirectResponse|Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $encodedPassword = $encoder->encodePassword($user, $user->getPlainPassword());

            $user->setRoles(['ROLE_AUTHOR'])
                 ->setLocked(true)
                 ->setPassword($encodedPassword)
                 ->setConfirmationToken(rtrim(strtr(base64_encode(random_bytes(20)), '+/', '-_'), '='))
                 ->setPlainPassword(null);
            $em->persist($user);
            $em->flush();

            /** @var \Ood\UserBundle\Services\Messaging $messaging */
            $messaging = $this->container->get('ood_user.resetting.messaging');
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
     * @throws NotFoundHttpException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     *
     * @return RedirectResponse|Response
     */
    public function checkEmailAction(): Response
    {
        $email = $this->get('session')->get('ood_user_registration/email');

        if (empty($email)) {
            return $this->redirectToRoute('ood_user_registration_register');
        }

        $this->get('session')->remove('ood_user_registration/email');
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->loadUserByUsername($email);

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
     * @param string  $token
     *
     * @throws NotFoundHttpException*
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \LogicException
     *
     * @return RedirectResponse
     */
    public function confirmAction(string $token): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        /** @var \Ood\UserBundle\Repository\UserRepository $repository */
        $repository = $em->getRepository(User::class);
        /** @var User $user */
        $user = $repository->findByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(
                sprintf('The user with "confirmation token" does not exist for value "%s"', $token)
            );
        }

        $user->setConfirmationToken(null)
             ->setLocked(false);
        $em->persist($user);
        $em->flush();

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
