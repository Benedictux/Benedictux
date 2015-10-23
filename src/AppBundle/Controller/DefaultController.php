<?php
//src/AppBundle/Controller/DefaultController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//Need for createAction
use AppBundle\Entity\Post;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findAll();
            //->findLatest();

        return $this->render('default/index.html.twig', array(
          'posts' => $posts
        ));
    }

    /**
     * @Route("/{slug}", name="show_post")
     */
    public function showAction(Post $post)
    {
        return $this->render('default/show.html.twig', array(
          'post' => $post
          ));
    }

    /**
     * @Route("/create", name="create_post")
     */
    public function createAction()
    {
        $post = new Post();

        //if ($form->isSubmitted() && $form->isValid()) {
        $post->setTitle('Mon premier post');

        $slug = $this->get('app.slugger')->slugify($post->getTitle());
        $post->setSlug($slug);

        $post->setContent('Voici officiellement le premier post d/inauguration du site Benedictux.com');
        $post->setAuthorEmail('B3n3dictux@gmail.com');

        $em = $this->getDoctrine()->getManager();

        $em->persist($post);
        $em->flush();

        return new Response('Created post id '.$post->getId());
    }

    /**
     * @Route("/update/{id}", name="update_post")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('AppBundle:Post')->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }

        $post->setTitle('New post name!');
        $slug = $this->get('app.slugger')->slugify($post->getTitle());
        $post->setSlug($slug);

        $em->flush();

        return $this->redirectToRoute('homepage');
    }
}
