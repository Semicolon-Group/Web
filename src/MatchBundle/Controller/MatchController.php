<?php

namespace MatchBundle\Controller;

use BaseBundle\Entity\Enumerations\BodyType;
use BaseBundle\Entity\Enumerations\CivilStatus;
use BaseBundle\Entity\Enumerations\DistanceType;
use BaseBundle\Entity\Enumerations\LastLoginType;
use BaseBundle\Entity\Enumerations\Religion;
use MatchBundle\Service\MatchCardService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MatchController extends Controller
{
    /**
     * @Route("/", name="match")
     */
    public function matchesAction()
    {
        $user = $this->container->get("security.token_storage")->getToken()->getUser();
        $cards = MatchCardService::getMatches($this->getDoctrine(), $user);
        return $this->render('MatchBundle:Match:matches.html.twig', array(
            'cards' => $cards,
            'bodyTypes' => BodyType::getNames(),
            'religions' => Religion::getNames(),
            'maritalStatuses' => CivilStatus::getNames(),
            'distance' => DistanceType::getNames(),
            'lastLogin' => LastLoginType::getNames()
        ));
    }
}
