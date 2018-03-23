<?php

namespace Business\ChartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/events", name="business_event_charts")
     */
    public function eventChartsAction()
    {
        return $this->render('BusinessChartBundle:Default:eventCharts.html.twig');
    }

    /**
     * @Route("/adverts", name="business_advert_charts")
     */
    public function advertChartsAction(){
        return $this->render('BusinessChartBundle:Default:advertCharts.html.twig');
    }
}
