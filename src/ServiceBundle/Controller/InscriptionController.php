<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 24/04/2018
 * Time: 16:50
 */

namespace ServiceBundle\Controller;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



/**
 * Inscription controller.
 *
 * @Route("/inscription/")
 */
class InscriptionController extends Controller
{
    /**
     * Lists all Signals entities.
     *
     * @Route("new", name="User")
     */
    public function InscriptionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new \Proxies\__CG__\BaseBundle\Entity\User();
        $user->setFirstname($request->get('firstname'));
        $user->setLastname($request->get('lastname'));
        $user->setEmail($request->get('email'));
        $user->setPassword($request->get('password'));
        $user->setUsername($request->get('username'));
        $user->setEnabled(1);
        $user->setPhone($request->get('phone'));
        $user->setAbout($request->get('about'));
        $gender=$request->get('gender');
        if ($gender=="false")
        {$gender=0;
        } else
        $gender=1;
        $user->setGender($gender);
        $smoker=$request->get('smoker');
        if ($smoker=="false")
        {$smoker=0;
        } else
            $smoker=1;
        $user->setSmoker($smoker);
        $drinker=$request->get('drinker');
        if ($drinker=="false")
        {$drinker=0;
        } else{
            $drinker=1;}
        $user->setDrinker($drinker);
        $date=new \DateTime($request->get('birth'));
        $user->setBirthDate($date);
        $user->setBodyType($request->get('bodytype'));
        $user->setChildrenNumber($request->get('childrennbr'));
        $user->setMinAge($request->get('minage'));
        $user->setMaxAge($request->get('maxage'));
        $user->setRelegion($request->get('relegion'));
        $user->setRelegionImportance($request->get('relegionImportance'));
        $user->setCivilStatus($request->get('civilStatus'));
        $user->setHeight($request->get('height'));
        $em->persist($user);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        // Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer(array($normalizer));

        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);

    }

}