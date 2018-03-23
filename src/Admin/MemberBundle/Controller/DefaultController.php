<?php

namespace Admin\MemberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_members")
     */
    public function indexAction()
    {
        return $this->render('AdminMemberBundle:Default:index.html.twig');
    }
}
