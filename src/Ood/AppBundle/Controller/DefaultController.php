<?php
/**
 * This file is part of oc_snowtricks project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/02
 */
namespace Ood\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 *
 * @package Ood\AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @throws \LogicException
     */
    public function homepageAction()
    {
        return $this->render('@OodApp/Default/homepage.html.twig', []);
    }
}
