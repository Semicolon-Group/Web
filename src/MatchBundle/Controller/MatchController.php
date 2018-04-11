<?php

namespace MatchBundle\Controller;

use BaseBundle\Entity\Enumerations\BodyType;
use BaseBundle\Entity\Enumerations\CivilStatus;
use BaseBundle\Entity\Enumerations\DistanceType;
use BaseBundle\Entity\Enumerations\LastLoginType;
use BaseBundle\Entity\Enumerations\Religion;
use http\Env\Response;
use MatchBundle\Entity\Filter;
use MatchBundle\Service\MatchCardService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MatchController extends Controller
{
    /**
     * @Route("/match", name="match")
     */
    public function matchesAction()
    {
        $user = $this->getUser();
        $cards = MatchCardService::getMatches($this->getDoctrine(), $user, null);
        return $this->render('MatchBundle:Match:matches.html.twig', array(
            'cards' => $cards,
            'bodyTypes' => BodyType::getNames(),
            'religions' => Religion::getNames(),
            'maritalStatuses' => CivilStatus::getNames(),
            'distance' => DistanceType::getNames(),
            'lastLogin' => LastLoginType::getNames(),
            'gender' => $user->getGender()
        ));
    }


    /**
     * @Route("/filter", name="filter")
     */
    public function filterAction(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $filter = new Filter();
            $filter->setBody($request->get('body'));
            $filter->setReligion($request->get('religion'));
            $filter->setStatus($request->get('status'));
            $filter->setSmokes($request->get('smokes'));
            $filter->setDrinks($request->get('drinks'));
            $filter->setDistance($request->get('distance'));
            $filter->setLogin($request->get('login'));
            $filter->setMinAge($request->get('minAge'));
            $filter->setMaxAge($request->get('maxAge'));
            $filter->setMinHeight($request->get('minHeight'));
            $filter->setMaxHeight($request->get('maxHeight'));

            $cards = MatchCardService::getMatches($this->getDoctrine(), $this->getUser(), $filter);
            $content = [];
            $content [] = $this->render('MatchBundle:Match:matchCards.html.twig',['cards' => $cards])->getContent();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $content = $serializer->normalize($content);
            return new JsonResponse($content);
        }
    }

}
