<?php

namespace Admin\ChartBundle\Controller;

use BaseBundle\Entity\User;
use DateTime;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MemberChartsController extends Controller
{
    /**
     * @Route("/member", name="admin_member_charts")
     */
    public function getMemberChartsAction(){

        $obj = $this->getGenderPieChart();

        return $this->render('@AdminChart/MemberCharts/member_charts.html.twig',
            array(
                'member_number' => $this->getMemberNumberChart(),
                'member_gender' => $obj['chart'],
                'total_number' => $obj['total_number']
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
        $counts = $this->getDoctrine()->getRepository(User::class)->getCountByMonth();
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
        $ob->title->text('Member number in '.(new DateTime)->format("Y"));
        $ob->xAxis->title(array('text' => "Months"));
        $ob->yAxis->title(array('text' => "Member number"));
        $ob->xAxis->categories($months);
        $ob->series($series);
        return $ob;
    }

    private function getGenderPieChart(){
        $ob = new Highchart();
        $ob->chart->renderTo('piechart');
        $ob->title->text('Gender Percentage');
        $ob->plotOptions->pie(array(
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => false),
            'showInLegend' => true
        ));
        $stats = $this->getDoctrine()->getRepository(User::class)->getGenderNumber();
        $totalMembers=0;
        foreach($stats as $stat) {
            $totalMembers+=$stat['total'];
        }
        $data= array();
        foreach($stats as $stat) {
            $res=array();
            array_push($res,((int)$stat['gender'])==0?'Female':'Male',(($stat['total']) *100)/$totalMembers);
            //var_dump($stat);
            array_push($data,$res);
        }
        // var_dump($data);
        $ob->series(array(array('type' => 'pie','name' => 'Gender share', 'data' => $data)));
        return ['chart' => $ob, 'total_number' => $totalMembers];
    }



}
