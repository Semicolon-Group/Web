<?php
/**
 * Created by PhpStorm.
 * User: vaider
 * Date: 25/04/2018
 * Time: 15:59
 */

namespace ServiceBundle\Controller;

use BaseBundle\Entity\Post;
use BaseBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\BaseBundle\BaseBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use BaseBundle\Entity\Notification;
class NotificationController extends Controller
{

    /**
     * show notification.
     *
     * @Route("/allnotification", name="notification_getall")
     */
    public function AllNotificationAction()
    {
        $notifs = $this->getDoctrine()->getManager()
            ->getRepository('BaseBundle:Notification')
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($notifs);
        return new JsonResponse($formatted);
    }

    /**
     * Creates a new notificatoin entity.
     *
     * @Route("/newNotif/{id}/{content}", name="notification_add")
     */
    public function addNotificationAction(Request $request,$id,$content){
            $em=$this->getDoctrine()->getManager();
            $notif = new Notification();

            $notif->setContent($content);
            $notif->setSender($this->getDoctrine()->getRepository(User::class)->find($id));
            //receiver online//photo_id
            //$notif->setPhotoId($this->getDoctrine()->getRepository(User::class)->find($id));
            $notif->setPostId($this->getDoctrine()->getRepository(Post::class)->find($id));
            $notif->setSeen(false);
            $notif->setType(1);
            $em->persist($notif);

            $em->flush();
            $this->getDoctrine()->getManager()->flush();

            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($notif);
            return new JsonResponse($formatted);
    }

    /**
     * @Route("/notif/{id}",name="notif")
     */
    public function CommentAction($id)

    {
        $em = $this->getDoctrine()->getManager();
        $RAW_QUERY = 'SELECT user.username , notification.content , notification.date FROM user INNER JOIN notification on user.id=notification.sender_id WHERE notification.receiver_id=:id';


        $statement = $em->getConnection()->prepare($RAW_QUERY);

        // Set parameters

        $statement->bindValue('id', $id);

        $statement->execute();


        $comment = $statement->fetchAll();
        $normalizer = new GetSetMethodNormalizer();
        //  $normalizer->setIgnoredAttributes(array('idUser','idClasse','imageFile'));


        $normalizer->setIgnoredAttributes(array('roles', 'groups', 'groupNames'));
        $encoder = new JsonEncoder();

        $serializer = new Serializer(array(new DateTimeNormalizer('d/m/Y'), $normalizer), array($encoder));
        $serializer->serialize($comment, 'json');

        $a = $serializer->normalize($comment);
        $response = new JsonResponse($a);
        //  $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}