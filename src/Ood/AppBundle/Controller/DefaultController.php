<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */

namespace Ood\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 *
 * @package Ood\AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * Show homepage
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function homepageAction()
    {
        return $this->render('@OodApp/Default/homepage.html.twig');
    }

    /**
     * Show dashboard page
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function dashboardAction()
    {
        return $this->render('@OodApp/Default/dashboard.html.twig');
    }
}
