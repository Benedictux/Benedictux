<?php
//src/AppBundle/Controller/PostController.php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Post;
use AppBundle\Form\Type\PostType;
use AppBundle\Form\Type\PostEditType;

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
        $post->setAuthorEmail('dipsetm12@hotmail.fr');
        $form = $this->createForm(new PostType(), $post);

        //$data = $this->get('app.scanner')->scanDirectory($_SERVER['DOCUMENT_ROOT'].'Benedictux/web/uploads');
        //$data = $this->get('app.scanner')->scanDirectory($this->get('request')->getBasePath());
        //$scanned_directory = array_diff(scandir('./web'), array('..', '.'));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

                $slug = $this->get('app.slugger')->slugify($post->getTitle());
                $post->setSlug($slug);
    
                if (get_magic_quotes_gpc()) {
                    $content = stripslashes($post->getContent());
                    $content = $this->get('app.parser')->parserTexarea($content);
                    $post->setContent($content);}
                else{
                    $content = $this->get('app.parser')->parserTexarea($post->getContent());
                    $post->setContent($content);}

            $em->persist($post);
            $em->flush();
            
            return $this->redirectToRoute('accueil');
        }

        return $this->render('app/postCreate.html.twig', array(
            'form' => $form->createView(),
            //'data' => $data,
        ));
    }

    /**
     * @Route("/update/{slug}", name="update_post")
     */
    public function updateAction(Post $post, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (!$post) {
            throw $this->createNotFoundException(
                'No post found'
            );
        }

        $form = $this->createForm(new PostEditType(), $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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

    /**
     * @Route("/delete/{slug}", name="delete_post")
     */
    public function deleteAction(Post $post, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (null === $post) {
            throw new NotFoundHttpException("Ce Post n'existe pas.");
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $em->remove($post);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', "Le post a bien été supprimé.");
            return $this->redirectToRoute('accueil');
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('app/postDelete.html.twig', array(
            'post' => $post,
            'form'   => $form->createView()
        ));
    }
}
