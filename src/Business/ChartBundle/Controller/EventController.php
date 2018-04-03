<?php

namespace Business\ChartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EventController extends Controller
{
    /**
     * @Route("/events", name="business_event_charts")
     */
    public function eventChartsAction()
    {
        return $this->render('BusinessChartBundle:Event:event_chart.html.twig');
    }
}
