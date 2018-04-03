<?php

namespace Business\ChartBundle\Controller;

use BaseBundle\Entity\Advert;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdvertController extends Controller
{
    /**
     * @Route("/adverts", name="business_advert_charts")
     */
    public function advertChartsAction(){
        $ads = $this->getTopAdvertsOrderByClicks();
        return $this->render('BusinessChartBundle:Advert:advert_chart.html.twig', array(
            'ads' => $ads,
        ));
    }

    private function getTopAdvertsOrderByClicks(){
        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        return $this->getDoctrine()->getRepository(Advert::class)->findBy(array('business' => $user));
    }
}
