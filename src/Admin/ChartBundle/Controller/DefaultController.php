<?php

namespace Admin\ChartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_charts")
     */
    public function indexAction()
    {
        return $this->render('AdminChartBundle:Default:index.html.twig');
    }
}
