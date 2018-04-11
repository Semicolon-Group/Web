<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    /**
     * {@inheritDoc}
     */
    public function renderLogin(array $data)
    {
        $requestStack = $this->container->get('request_stack');
        $masterRequest = $requestStack->getMasterRequest(); // this is the call that breaks ESI
        $requestAttributes = $masterRequest->attributes;

        unset($_SESSION['partId']);
        if ('business_login' === $requestAttributes->get('_route')) {
            $template = sprintf('BusinessFOSSecurityBundle:Security:login.html.twig');
        }else if('admin_login' === $requestAttributes->get('_route')){
            $template = sprintf('AdminFOSSecurityBundle:Security:login.html.twig');
        }else {
            $template = sprintf('FOSUserBundle:Security:login.html.twig');
        }

        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
