<?php

namespace Business\ChartBundle\Controller;

use BaseBundle\Entity\Advert;
use BaseBundle\Entity\User;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zend\Json\Expr;

class AdvertController extends Controller
{
    /**
     * @Route("/adverts", name="business_advert_charts")
     */
    public function advertChartsAction(){
        return $this->render('BusinessChartBundle:Advert:advert_chart.html.twig', array(
            'ad_chart' => $this->getTopAdvertsOrderByClicks()
        ));
    }

    private function getTopAdvertsOrderByClicks(){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $datas = $this->getDoctrine()->getRepository(Advert::class)->getTopFiveAdverts($user);

        $categories= array();
        $nbMembers=array();

        foreach($datas as $data) {
            array_push($categories,$data->getId());
            array_push($nbMembers,(int)$data->getClicks());
        }
        $series = array(
            array(
                'name' => 'Clicks',
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
                    'text' => 'clicks',
                    'style' => array('color' => '#4572A7')
                ),
            ),
        );

        $ob = new Highchart();
        $ob->chart->renderTo('barchart'); // The #id of the divwhere to render the chart
        $ob->chart->type('column');
        $ob->title->text('Top 5 adverts');
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


}
