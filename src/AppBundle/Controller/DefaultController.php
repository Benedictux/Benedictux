<?php
//src/AppBundle/Controller/DefaultController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // $posts = $this->getDoctrine()
        //     ->getRepository('AppBundle:Post')
        //     ->findLatest();

        //return $this->render('default/index.html.twig', array(
        //   'posts' => $posts
        //));
        return $this->render('default/index.html.twig');
    }
}