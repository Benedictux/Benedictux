<?php
//src/AppBundle/Controller/CoreController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoreController extends Controller
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueilAction()
    {
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {

        return $this->render('app/contact.html.twig');
    }
}