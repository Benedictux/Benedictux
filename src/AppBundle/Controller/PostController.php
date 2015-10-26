<?php
//src/AppBundle/Controller/PostController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;

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
        $form = $this->createForm(new PostType(), $post);

        if ($form->handleRequest($request)->isSubmitted() && $form->handleRequest($request)->isValid()) {
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

        $form = $this->createForm(new PostType(), $post);

        if ($form->handleRequest($request)->isSubmitted() && $form->handleRequest($request)->isValid()) {
            $slug = $this->get('app.slugger')->slugify($post->getTitle());
            $post->setSlug($slug);
            $post->setUpdatedAt(new \DateTime());

            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->render('app/postUpdate.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
