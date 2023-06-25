<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProductRepository;

class BlogsController extends AbstractController
{
    /**
     * @Route("/blogs", name="app_blogs")
     */
    public function index(PostRepository $re,ProductRepository $Product, CategoryRepository $rep, SessionInterface $session): Response
    {
        $post = $re->findBy([]);
        $total_count =count($post);
        $count_per_page=5;

        $total_pages=ceil($total_count/$count_per_page);
        if(isset($_GET['page'])){
            $page=$_GET['page'];
            
            $total_pages=ceil($total_count/$count_per_page);

            if($total_count<=$count_per_page){
                $page=1;
            }
            if(($page*$count_per_page>$total_count)){
                $page=$total_pages;
            }
    
            $myLimit=5;
            $myOffset=0;
            if($page>1){
                $myOffset= $count_per_page * ($page-1); 
            }
            
            $pginateStartIndex = ($myOffset > 0) ? $myOffset : $myOffset;
        }else{
            $myLimit=5;
		    $pginateStartIndex=0;
        }
        $post = $re->findBy(array(),array('id' => 'desc'),
        $myLimit,
        $pginateStartIndex);
        
        $Category = $rep->findBy([]);
        $basket = $session->get('basket', []);
        $Recent = $re->findBy(array(), array('id' => 'desc'));
        $service = $Product->findBy(array(), array('id' => 'desc'));
        return $this->render('blogs/index.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $post,
            'Category' => $Category,
            'basket'=>$basket,
            'Recent' => $Recent,
            'service' => $service,
            'total_pages' => $total_pages,
        ]);
    }

    /**
     * @Route("blogs/{id}" )
     */
    public function details($id,  PostRepository $repo, CategoryRepository $reps, SessionInterface $session): Response
    {
        // print_r($id);
        // die;
        $post = $repo->find($id);
        $Category = $reps->findBy([]);
         $Recent = $repo->findBy(array(), array('id' => 'desc'));
         $basket = $session->get('basket', []);
        return $this->render('blogs/detail.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $post,
            'Category' => $Category,
            'Recent' => $Recent,
            'basket'=> $basket,
        ]);
    }
}
