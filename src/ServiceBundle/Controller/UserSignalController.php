<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 22/04/2018
 * Time: 13:44
 */


namespace ServiceBundle\Controller;
use BaseBundle\Entity\Enumerations\SignalReason;
use BaseBundle\Entity\UserSignal;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



/**
 * Signal controller.
 *
 * @Route("/signal/")
 */

class UserSignalController extends Controller
{

    /**
     * Lists all Signals entities.
     *
     * @Route("new/{idS}/{idR}/{content}/{reason}", name="SignalAjout")
     */
    public function addSignalAction(Request $request , $idS, $idR, $content, $reason)
    {
        $em = $this->getDoctrine()->getManager();
        $signal = new UserSignal();


        $signal->setState(false);
        $signal->setSender($this->getDoctrine()->getRepository(User::class)->find($idS));
        $signal->setReceiver($this->getDoctrine()->getRepository(User::class)->find($idR));
        $signal->setContent($content);
        /**$signalreason = $request->get('reason');
        $signalreason = SignalReason::getEnumAsArray()[$signalreason] ;*/
        $signal->setReason($reason);
        $em->persist($signal);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer(array($normalizer));

        $formatted = $serializer->normalize($signal);
        return new JsonResponse($formatted);

    }
}