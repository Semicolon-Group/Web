<?php
/**
 * Created by PhpStorm.
 * User: vaider
 * Date: 25/04/2018
 * Time: 00:03
 */

namespace ServiceBundle\Controller;

use BaseBundle\Entity\Choice;
use BaseBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\BaseBundle\BaseBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use BaseBundle\Entity\Answer;


class AnswerController extends Controller
{

    /**
     * show answer.
     *
     * @Route("/allanswer", name="haithem_answer_getall")
     */
    public function AllAnswerAction()
    {
        $answers = $this->getDoctrine()->getManager()
            ->getRepository('BaseBundle:Answer')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($answers);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/answers/getQuestion", name="haithem_get_question");
     */
    public function getQuestionAction(Request $request){
        if($request->isXmlHttpRequest()){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(1);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));

            $question = $this->getDoctrine()->getRepository(Question::class)->find($request->get('questionId'));
            $choices = $this->getDoctrine()->getRepository(Choice::class)->findBy(array('question' => $question));
            foreach ($choices as $choice){
                $question->addChoice($choice);
            }
            $topics = Topic::getEnumAsArray();
            $importances = Importance::getEnumAsArray();

            $toSend = ['question' => $question];

            $data = $serializer->normalize($toSend);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @Route("/addanswer", name="haithem_add_answer");
     */
    public function addAnswerAction(Request $request){

        $em=$this->getDoctrine()->getManager();
        $answer = new Answer();
        $dom = $request->get('answer');


        $answer->setQuestion($this->getDoctrine()->getRepository(Question::class)->find($dom['question_id']));
        $answer->setSelectedChoice($this->getDoctrine()->getRepository(Choice::class)->find($dom['selected_choice_id']));


        $acceptableChoices = $dom['accepted_choices_ids'];
        foreach ($acceptableChoices as $c){
            $answer->addChoice($this->getDoctrine()->getRepository(Choice::class)->find($c));
        }

        $em->persist($answer);

        $em->flush();
        $this->getDoctrine()->getManager()->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($answer);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/question",name="haithem_question")
     */
    public function show_queApi()

    {
        $em = $this->getDoctrine()->getManager();
        $fichier = $em->getRepository('BaseBundle:Question')->findAll();

        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes(array('choices','topic'));


        // $normalizer->setIgnoredAttributes(array('idClasse'));
        $encoder = new JsonEncoder();

        $serializer = new Serializer(array(new DateTimeNormalizer('d/m/Y'), $normalizer), array($encoder));
        $serializer->serialize($fichier, 'json');

        $a = $serializer->normalize($fichier);
        $response = new JsonResponse($a);
        //  $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
    /**
     * @Route("/quest",name="haithem_quest")
     */
    public function show_queiApi(Request $request)

    {
        $em = $this->getDoctrine()->getManager();
        $fichier = $em->getRepository('BaseBundle:Choice')->findBy(array('question'=>$request->get('id')));

        $normalizer = new GetSetMethodNormalizer();
        $normalizer->setIgnoredAttributes(array('question','answer'));


        // $normalizer->setIgnoredAttributes(array('idClasse'));
        $encoder = new JsonEncoder();

        $serializer = new Serializer(array(new DateTimeNormalizer('d/m/Y'), $normalizer), array($encoder));
        $serializer->serialize($fichier, 'json');

        $a = $serializer->normalize($fichier);
        $response = new JsonResponse($a);
        //  $response->headers->set('Content-Type', 'application/json');

        return $response;

    }


    /**
     * @Route("/new",name="haithem_new")
     */

    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $select = $em->getRepository('BaseBundle:Choice')->findOneBy(array('id' => $request->get('id_selected')));
        $question = $em->getRepository('BaseBundle:Question')->findOneBy(array('id' => $request->get('id_question')));
        $user = $em->getRepository('BaseBundle:User')->findOneBy(array('id' => $request->get('id_user')));

        $ans = new Answer();
       $ans->setSelectedChoice($select);
        $ans->setQuestion($question);
        $ans->setUser($user);

        $em->persist($ans);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);

        $formatted = $serializer->normalize($ans);
        $response = new JsonResponse($formatted);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/reponse",name="haithem_reponse")
     */
    public function CommentAction()

    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT quest.question ,choice.choice FROM question AS quest INNER JOIN answer AS answer  INNER JOIN choice as choice on answer.question_id=quest.id and answer.selected_choice_id=choice.id';


        $statement = $em->getConnection()->prepare($RAW_QUERY);

        // Set parameters

     //   $statement->bindValue('id', $id);

        $statement->execute();


        $comment = $statement->fetchAll();
        $normalizer = new GetSetMethodNormalizer();
       //   $normalizer->setIgnoredAttributes(array('importance'));


        //$normalizer->setIgnoredAttributes(array('roles', 'groups', 'groupNames'));
        $encoder = new JsonEncoder();

        $serializer = new Serializer(array(new DateTimeNormalizer('d/m/Y'), $normalizer), array($encoder));
        $serializer->serialize($comment, 'json');

        $a = $serializer->normalize($comment);
        $response = new JsonResponse($a);
        //  $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}