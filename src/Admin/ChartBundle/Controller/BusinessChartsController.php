<?php

namespace Admin\ChartBundle\Controller;

use BaseBundle\BaseBundle;
use BaseBundle\Entity\Enumerations\Categorie;
use BaseBundle\Entity\Enumerations\Topic;
use BaseBundle\Entity\User;
use DateTime;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Zend\Json\Expr;

class BusinessChartsController extends Controller
{
    /**
     * @Route("/business", name="admin_business_charts")
     */
    public function getBusinessChartsAction(){
        $o = $this->getMemberNumberChart();

        return $this->render('AdminChartBundle:BusinessCharts:business_charts.html.twig', array(
            'chart' => $o['chart'],
            'cumulative' => $o['chart2'],
            'category' => $this->getBusinessByCategory()
        ));
    }

    private function containsMonth($counts, $month){
        foreach ($counts as $count){
            if($count['creation_month'] == $month)
                return $count;
        }
        return null;
    }

    private function getMemberNumberChart(){
        $months = array(
            0 => 'January',
            1 => 'February',
            2 => 'March',
            3 => 'April',
            4 => 'May',
            5 => 'June',
            6 => 'July',
            7 => 'August',
            8 => 'September',
            9 => 'October',
            10 => 'November',
            11 => 'December'
        );
        $counts = $this->getDoctrine()->getRepository(User::class)->getBusinessCountByMonth();
        $tabs = array();
        $tabs2 = array();
        for($i=1; $i<=12; $i++){
            $count = $this->containsMonth($counts, $i);
            if($i==1){
                array_push($tabs2, (int)$count['total']);
            }else{
                array_push($tabs2, $tabs2[$i-2]+(int)$count['total']);
            }
            if ($count!=null){
                array_push($tabs, (int)$count['total']);
            }else{
                array_push($tabs, 0);
            }
        }
        $series = array(
            array("name" => "Business inscription number", "data" => $tabs)
        );
        $ob = new Highchart();
        $ob->chart->renderTo('linechart'); // #id du div où afficher le graphe
        $ob->title->text('Business Inscriptions number by month in '.(new DateTime)->format("Y"));
        $ob->xAxis->title(array('text' => "Months"));
        $ob->yAxis->title(array('text' => "Business inscriptions number"));
        $ob->xAxis->categories($months);
        $ob->series($series);


        $series2 = array(
            array("name" => "Business cumulative number", "data" => $tabs2)
        );
        $ob2 = new Highchart();
        $ob2->chart->renderTo('cumlinechart'); // #id du div où afficher le graphe
        $ob2->title->text('Business cumulative number in '.(new DateTime)->format("Y"));
        $ob2->xAxis->title(array('text' => "Months"));
        $ob2->yAxis->title(array('text' => "Business cumulative number"));
        $ob2->xAxis->categories($months);
        $ob2->series($series2);
        return ['chart' => $ob, 'chart2' => $ob2];
    }


    private function getBusinessByCategory(){
        $datas = $this->getDoctrine()->getRepository(User::class)->getBusinessCountByCategory();

        $categories= array();
        $nbMembers=array();

        foreach($datas as $data) {
            array_push($categories,Categorie::getName($data['category']));
            array_push($nbMembers,(int)$data['total']);
        }
        $series = array(
            array(
                'name' => 'Businesses',
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
                    'text' => 'Number of businesses',
                    'style' => array('color' => '#4572A7')
                ),
            ),
        );

        $ob = new Highchart();
        $ob->chart->renderTo('barchart'); // The #id of the divwhere to render the chart
        $ob->chart->type('column');
        $ob->title->text('Number of businesses by category');
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
