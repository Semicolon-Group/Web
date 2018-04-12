<?php

namespace Admin\HomeBundle\Controller;

use BaseBundle\Entity\Enumerations\PostType;
use BaseBundle\Entity\Post;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_home")
     */
    public function indexAction()
    {
        return $this->redirectToRoute('admin_questions');
    }

    /**
     * @Route("/announce", name="announce")
     */
    public function announceAction(Request $request){
        if($request->isXmlHttpRequest()){
            $post = new Post();
            $post->setUser($this->getUser());
            $post->setDate(new DateTime('now'));
            $post->setContent($request->get('text'));

            $this->getDoctrine()->getManager()->persist($post);
            $this->getDoctrine()->getManager()->flush();

            return new Response();
        }
    }
}
