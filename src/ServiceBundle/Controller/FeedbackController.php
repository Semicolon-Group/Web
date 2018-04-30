<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 22/04/2018
 * Time: 13:41
 */

namespace ServiceBundle\Controller;
use BaseBundle\Entity\Feedback;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



/**
 * Feedback controller.
 *
 * @Route("/feed/")
 */
class FeedbackController extends Controller
{

    /**
     * Lists all feedback entities.
     *
     * @Route("new/{id}/{content}", name="feedbackajout")
     */
    public function addFeedAction(Request $request , $id, $content )
    {
        $em = $this->getDoctrine()->getManager();
        $feedback = new Feedback();
        $feedback->setContent($content);
        $feedback->setState(false);
        $feedback->setSender($this->getDoctrine()->getRepository(User::class)->find($id));
        $em->persist($feedback);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($feedback);
        return new JsonResponse($formatted);

    }
}