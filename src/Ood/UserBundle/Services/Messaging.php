<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/03
 */

namespace Ood\UserBundle\Services;

use Swift_Mailer;
use Twig_Environment;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Ood\UserBundle\Entity\User;

/**
 * Class Messaging
 *
 * @package Ood\UserBundle\Services
 */
class Messaging
{
    // ---------------------
    // PROTECTED MEMBERS
    // ---------------------

    /** @var Swift_Mailer */
    protected $mailer;

    /** @var Twig_Environment */
    protected $twig;

    /** @var string */
    protected $senderNoReply;

    /** @var string */
    protected $senderName;

    /**
     * Messaging constructor.
     *
     * @param Swift_Mailer          $mailer
     * @param Twig_Environment      $twig
     * @param UrlGeneratorInterface $router
     * @param string                $senderNoReply
     * @param string                $senderName
     */
    public function __construct(
        Swift_Mailer $mailer,
        Twig_Environment $twig,
        UrlGeneratorInterface $router,
        string $senderNoReply,
        string $senderName
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->router = $router;
        $this->senderNoReply = $senderNoReply;
        $this->senderName = $senderName;
    }

    /**
     * Send an email to confirm user registration
     *
     * @param User $user
     *
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function confirmationRegistrationRequest(User $user)
    {
        $template = $this->twig->load('OodUserBundle:Registration:email.html.twig');
        $urlConfirmation = $this->router->generate(
            'ood_user_registration_confirm',
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $message = \Swift_Message::newInstance()
                                 ->setSubject($template->renderBlock('subject', ['username' => $user->getUsername()]))
                                 ->setContentType('text/html')
                                 ->setFrom($this->senderNoReply, $this->senderName)
                                 ->setTo($user->getEmail())
                                 ->setBody(
                                     $template->renderBlock(
                                         'body_html',
                                         [
                                             'urlConfirmation' => $urlConfirmation,
                                             'username'        => $user->getUsername()
                                         ]
                                     )
                                 );

        $this->mailer->send($message);
    }


    /**
     * Send email for password resetting request
     *
     * @param User $user
     * @throws \Symfony\Component\Routing\Exception\InvalidParameterException
     * @throws \Symfony\Component\Routing\Exception\MissingMandatoryParametersException
     * @throws \Symfony\Component\Routing\Exception\RouteNotFoundException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordResettingRequest(User $user)
    {
        $template = $this->twig->load('OodUserBundle:Resetting:email.html.twig');
        $urlConfirmation = $this->router->generate(
            'ood_user_resetting_reset',
            ['token' => $user->getConfirmationToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $message = \Swift_Message::newInstance()
                                 ->setSubject($template->renderBlock('subject'))
                                 ->setContentType('text/html')
                                 ->setFrom($this->senderNoReply, $this->senderName)
                                 ->setTo($user->getEmail())
                                 ->setBody(
                                     $template->renderBlock(
                                         'body_html',
                                         [
                                             'urlConfirmation' => $urlConfirmation,
                                             'username'        => $user->getUsername()
                                         ]
                                     )
                                 );

        $this->mailer->send($message);
    }
}
