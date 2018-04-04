<?php

namespace Business\ChartBundle\Controller;

use BaseBundle\Entity\Event;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zend\Json\Expr;

class EventController extends Controller
{
    /**
     * @Route("/events", name="business_event_charts")
     */
    public function eventChartsAction()
    {
        return $this->render('BusinessChartBundle:Event:event_chart.html.twig', array(
            'ev_chart' => $this->getTopEventsOrderByClicks()
        ));
    }

    private function getTopEventsOrderByClicks(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $datas = $this->getDoctrine()->getRepository(Event::class)->findBy(array('business' => $user));

        usort($datas, array($this, "cmp"));

        $categories= array();
        $nbMembers=array();

        foreach($datas as $data) {
            array_push($categories,$data->getTitle());
            array_push($nbMembers,(int)sizeof($data->getUser()));
        }
        $series = array(
            array(
                'name' => 'Participants',
                'type' => 'column',
                'yAxis' => 0,
                'data' => $nbMembers,
            )
        );
        $yData = array(
            array(
                'labels' => array(
                    'formatter' => new Expr('function () { return this.value + "" }'),
                    'style' => array('color' => '#4572A7')
                ),
                'gridLineWidth' => 0,
                'title' => array(
                    'text' => 'Participants',
                    'style' => array('color' => '#4572A7')
                ),
            ),
        );

        $ob = new Highchart();
        $ob->chart->renderTo('barchart'); // The #id of the divwhere to render the chart
        $ob->chart->type('column');
        $ob->title->text('Top 5 events');
        $ob->xAxis->categories($categories);
        $ob->yAxis($yData);
        $ob->legend->enabled(false);
        /*$formatter = new Expr('function () {
            var unit = {
            "Member": "member(s)",
            }[this.series.name];

            return this.x + ": <b>" + this.y + "</b> " + unit;
        }');
        $ob->tooltip->formatter($formatter);*/
        $ob->series($series);

        return $ob;
    }

    function cmp($a, $b)
    {
        return strcmp(sizeof($b->getUser()), sizeof($a->getUser()));
    }
}
