<?php

namespace Admin\ChartBundle\Controller;

use BaseBundle\Entity\User;
use DateTime;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class BusinessChartsController extends Controller
{
    /**
     * @Route("/business", name="admin_business_charts")
     */
    public function getBusinessChartsAction(){
        return $this->render('AdminChartBundle:BusinessCharts:business_charts.html.twig', array(
            'chart' => $this->getMemberNumberChart()
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
        for($i=1; $i<=12; $i++){
            $count = $this->containsMonth($counts, $i);
            if ($count!=null){
                array_push($tabs, (int)$count['total']);
            }else{
                array_push($tabs, 0);
            }
        }
        $series = array(
            array("name" => "Member number", "data" => $tabs)
        );
        $ob = new Highchart();
        $ob->chart->renderTo('linechart'); // #id du div où afficher le graphe
        $ob->title->text('Business number in '.(new DateTime)->format("Y"));
        $ob->xAxis->title(array('text' => "Months"));
        $ob->yAxis->title(array('text' => "Business number"));
        $ob->xAxis->categories($months);
        $ob->series($series);
        return $ob;
    }
}
