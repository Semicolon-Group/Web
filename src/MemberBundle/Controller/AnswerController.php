<?php

namespace MemberBundle\Controller;

use BaseBundle\Entity\Answer;
use BaseBundle\Entity\Choice;
use BaseBundle\Entity\Enumerations\Importance;
use BaseBundle\Entity\Enumerations\Topic;
use BaseBundle\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AnswerController extends Controller
{


    /**
     * @Route("/answer/generate", name="generate_answer")
     */
    public function generateAnswerAction(Request $request){
        if($request->isXmlHttpRequest()){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(2);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user' => $user));
            $answeredQuestions = array_map(function ($an){
                return $an->getQuestion();
            }, $answers);
            $questions = $this->getDoctrine()->getRepository(Question::class)->findAll();

            if(sizeof($answeredQuestions) != sizeof($questions)){
                $diffQuestions = array_udiff($questions, $answeredQuestions,
                    function ($obj_a, $obj_b) {
                        return $obj_a->getId() - $obj_b->getId();
                    }
                );
                /*foreach ($answeredQuestions as $aq){
                    for($i=0; $i<sizeof($questions); $i++){
                        if($questions[$i]->getId() == $aq->getId()){
                            unset($questions[$i]);
                            $questions = array_values($questions);
                            break;
                        }
                    }
                }*/


                //$questions = array_values($questions);
                $diffQuestions = array_values($diffQuestions);
                $questionIndex = rand ( 0 , sizeof($diffQuestions)-1 );
                $question = $diffQuestions[$questionIndex];
                $choices = $this->getDoctrine()->getRepository(Choice::class)->findBy(array('question' => $question));
                $toSend = array('question' => $question, 'choices' => $choices);
                $data = $serializer->normalize($toSend);

            }else{
                $data = null;
            }
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/answers", name="get_answers");
     */
    public function getAnswersAction(Request $request){
        if($request->isXmlHttpRequest()){
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(2);
            // Add Circular reference handler
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getId();
            });
            $serializer = new Serializer(array($normalizer));
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('user'=>$user));
            if(sizeof($answers)==0){
                return null;
            }else{
                $data = $serializer->normalize($answers);
                return new JsonResponse($data);
            }
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }



    /**
     * @Route("/answers/add", name="add_answer");
     */
    public function addAnswerAction(Request $request){
        if($request->isXmlHttpRequest()){
            $dom = $request->get('answer');
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            $answer = new Answer();
            $answer->setUser($user);
            $answer->setQuestion($this->getDoctrine()->getRepository(Question::class)->find($dom['question_id']));
            $answer->setSelectedChoice($this->getDoctrine()->getRepository(Choice::class)->find($dom['selected_choice_id']));
            $answer->setImportance($dom['importance_id']);
            $answer->setDate(new \DateTime());

            $acceptableChoices = $dom['accepted_choices_ids'];
            foreach ($acceptableChoices as $c){
                $answer->addChoice($this->getDoctrine()->getRepository(Choice::class)->find($c));
            }

            $this->getDoctrine()->getManager()->persist($answer);
            $this->getDoctrine()->getManager()->flush();
            return new Response(Response::HTTP_OK);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/answers/check", name="check_answer");
     */
    public function checkAnswerAction(Request $request){
        if($request->isXmlHttpRequest()){
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            $question = $this->getDoctrine()->getRepository(Question::class)->find($request->get('questionId'));
            $answers = $this->getDoctrine()->getRepository(Answer::class)->findBy(array('question' => $question, 'user' => $user));
            if(sizeof($answers)!=0){
                $resp = "true";
            }else{
                $resp = "false";
            }
            return new Response($resp);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/answers/getQuestion", name="get_question");
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

            $toSend = ['question' => $question, 'topics' => $topics, 'importances' => $importances];

            $data = $serializer->normalize($toSend);
            return new JsonResponse($data);
        }
        return new Response(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
