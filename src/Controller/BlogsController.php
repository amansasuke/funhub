<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;

class BlogsController extends AbstractController
{
    /**
     * @Route("/blogs", name="app_blogs")
     */
    public function index(PostRepository $re, CategoryRepository $rep): Response
    {
        $post = $re->findBy([]);
        $Category = $rep->findBy([]);
        
        return $this->render('blogs/index.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $post,
            'Category' => $Category,
        ]);
    }

    /**
     * @Route("blogs/{id}" )
     */
    public function details($id,  PostRepository $repo, CategoryRepository $reps): Response
    {
        // print_r($id);
        // die;
        $post = $repo->find($id);
        $Category = $reps->findBy([]);
         $Recent = $repo->findBy(array(), array('id' => 'desc'));
        
        return $this->render('blogs/detail.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $post,
            'Category' => $Category,
            'Recent' => $Recent,
        ]);
    }
}
