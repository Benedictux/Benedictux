<?php
//src/AppBundle/Controller/PostController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Post;//Need for createAction

class PostController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        $posts = $this->getDoctrine()
            ->getRepository('AppBundle:Post')
            ->findAll();
            //->findLatest();

        return $this->render('app/postIndex.html.twig', array(
          'posts' => $posts
        ));
    }

    /**
     * @Route("/show/{slug}", name="show_post")
     */
    public function showAction(Post $post)
    {
        return $this->render('app/postShow.html.twig', array(
          'post'   => $post
          ));
    }

    /**
     * @Route("/create", name="create_post")
     */
    public function createAction(Request $request)
    {
        // just setup a fresh $post object (remove the dummy data)
        $post = new Post();

        $form = $this->createFormBuilder($post)
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->add('authorEmail','email')
            ->add('published', 'checkbox')
            ->add('save', 'submit', array('label' => 'Create Post'))
            ->getForm(); // Génère le formulaire

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

                $slug = $this->get('app.slugger')->slugify($post->getTitle());
                $post->setSlug($slug);

            $em->persist($post);
            $em->flush();
            
            return $this->redirectToRoute('accueil');
        }

        return $this->render('app/postCreate.html.twig', array(
            'form' => $form->createView(),
        ));
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

        $post->setTitle('New post name');
        $slug = $this->get('app.slugger')->slugify($post->getTitle());
        $post->setSlug($slug);

        $em->flush();

        return $this->redirectToRoute('index');
    }
}
