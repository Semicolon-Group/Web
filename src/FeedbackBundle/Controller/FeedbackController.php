<?php

namespace FeedbackBundle\Controller;

use BaseBundle\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;


/**
 * Feedback controller.
 *
 * @Route("/")
 */

class FeedbackController extends Controller
{
    /**
     * Lists all feedback entities.
     *
     * @Route("/index", name="feedback_index")
     * @Method("GET")
     */

}
