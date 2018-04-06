<?php

namespace ExperienceBundle\Controller;

use BaseBundle\Entity\Address;
use BaseBundle\Entity\Experience;
use BaseBundle\Entity\Photo;
use BaseBundle\Form\PhotoType;
use DateTime;
use DoctrineExtensions\Query\Mysql\Date;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ExperienceController extends Controller
{
    /**
     * @Route("/", name="experiences")
     */
    public function experiencesAction(Request $request){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $otherExperiences = $this->getDoctrine()->getRepository(Experience::class)->getExperiences($user);
        $myExperiences = $this->getDoctrine()->getRepository(Experience::class)->findBy(array('user' => $user));

        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);
        return $this->render('ExperienceBundle:Experience:experiences.html.twig', array(
            'others_experiences' => $otherExperiences,
            'my_experiences' => $myExperiences,
            'photo_form' => $form->createView()
        ));
    }

    /**
     * @Route("/edit", name="edit_experience")
     */
    public function editExperienceAction(Request $request){
        if($request->isXmlHttpRequest()){
            $experience = $this->getDoctrine()->getRepository(Experience::class)->find($request->get('id'));
            $experience->setContent($request->get('content'));
            $experience->setDate(new DateTime($request->get('date')));
            $experience->setPlaceName($request->get('placeName'));
            $experience->setPhotoUrl($request->get('photoUrl'));
            $experience->getAddress()->setCity($request->get('city'));
            $experience->getAddress()->setCountry($request->get('country'));
            $experience->getAddress()->setLatitude($request->get('lat'));
            $experience->getAddress()->setLongitude($request->get('lng'));

            $this->getDoctrine()->getManager()->persist($experience);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/add", name="add_experience")
     */
    public function addExperienceAction(Request $request){
        if($request->isXmlHttpRequest()){
            $experience = new Experience();
            $experience->setContent($request->get('content'));
            $experience->setDate(new DateTime($request->get('date')));
            $experience->setPlaceName($request->get('placeName'));
            $experience->setPhotoUrl($request->get('photoUrl'));
            $experience->setUser($this->container->get('security.token_storage')->getToken()->getUser());
            $address = new Address();
            $address->setCity($request->get('city'));
            $address->setCountry($request->get('country'));
            $address->setLatitude($request->get('lat'));
            $address->setLongitude($request->get('lng'));

            $experience->setAddress($address);

            $this->getDoctrine()->getManager()->persist($experience);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/remove", name="remove_experience")
     */
    public function removeExperienceAction(Request $request){
        if($request->isXmlHttpRequest()){
            $experience = $this->getDoctrine()->getRepository(Experience::class)->find($request->get('id'));
            $this->getDoctrine()->getManager()->remove($experience);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/getMine", name="get_my_experiences")
     */
    public function getMyExperiencesAction(Request $request){
        if($request->isXmlHttpRequest()){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(2);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));

            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $myExperiences = $this->getDoctrine()->getRepository(Experience::class)->findBy(array('user' => $user));

            if(sizeof($myExperiences)==0){
                $data = null;
            }else{
                $data = $serializer->normalize($myExperiences);
            }
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/get", name="get_experience")
     */
    public function getExperienceAction(Request $request){
        if($request->isXmlHttpRequest()){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(2);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));

            $experience = $this->getDoctrine()->getRepository(Experience::class)->find($request->get('id'));

            $data = $serializer->normalize($experience);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

}
