<?php

namespace App\Controller;

use App\Entity\Services;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ServicesRepository;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\ProductRepository;
use App\Repository\DocumentsforproductRepository;
use App\Repository\DocforproRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use App\Entity\Eventbooking;
use App\Form\EventbookingType;
use App\Repository\EventbookingRepository;

use App\Entity\Mangereventbooking;
use App\Repository\MangereventbookingRepository;

use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $re ,  SessionInterface $session, EventbookingRepository $eventbookingRepository, Request $request, MangereventbookingRepository $MangereventbookingRepository): Response
    {
        $post = $re->findBy([]);

        $basket = $session->get('basket', []);

        


    if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userid= $user->getId();
        $useremail= $user->getEmail();
    }else{
        $useremail= '';
        $userid= '';
    }
        $mangerId='';
        $even = $eventbookingRepository->findBy(array('userid' => $userid));
        foreach( $even as $ev){
                if (!empty($ev->getManger())) {            
                $mangerId=$ev->getManger()->getId();
            }
        }
        $final=[];
        $dates=[];
    if(!empty($mangerId)){
        $eventbookingTime= [];
        $temp= [];
        $i= 0;
        $mangereventdata = $eventbookingRepository->findBy(array('manger' => $mangerId));
        foreach( $mangereventdata as $ev){
            $eventbookingTime[$i]['date'] = $ev->getBookingstart()->format('Y-m-d');
            $eventbookingTime[$i]['time'][] = $ev->getBookingtime()->format('H:i');
            // $eventbookingTime[$i]['time'] =$temp;
            // $temp[] = "";
            $i++;
        }

        $mangerevent =  $MangereventbookingRepository->findBy(array('mangerid'=>$mangerId));
        $mangereventTime = [];
        $k=0;
        foreach ($mangerevent as $key => $value) {
            $mangereventTime[$k]['mangerstartdate'] = $value->getStartdate()->format('Y-m-d');
            $mangereventTime[$k]['mangerenddate'] = $value->getEnddate()->format('Y-m-d');
            $mangereventTime[$k]['mangerstarttime'] = $value->getStarttime()->format('H:i');
            $mangereventTime[$k]['mangerendtime'] = $value->getEndtime()->format('H:i');
            $k++;
        }

        foreach($mangereventTime as $mangerev){
            $dates = array();
            $time = array();
            $i=0;
            $current = strtotime($mangerev['mangerstartdate']);
            $date2 = strtotime($mangerev['mangerenddate']);

            $currenttime = $mangerev['mangerstarttime'];
            $date2time = $mangerev['mangerendtime'];

            $stepVal = '+1 day';
            while( $current <= $date2 ) {
                $dates[$i]['date'] = date('Y-m-d', $current);
                $current = strtotime($stepVal, $current);
                while($currenttime <= $mangerev['mangerendtime']) {
                    $time[]=$currenttime;
                    $currenttime = date('H:i', strtotime($currenttime. ' + 1 hours'));   
                }
                $dates[$i]['time']=$time;
                $i++;
            }
        }

        //print_r($eventbookingTime);
        $final=[];
        $j=0;
        foreach ($eventbookingTime as $key => $value) {
            foreach ($dates as $key => $va) {
                if ($value['date'] == $va['date']) {
                    $result=array_diff($va['time'],$value['time']);
                    $final[$j]['date']= $value['date'];
                    $final[$j]['time']= $result;
                    $j++;
                }
            }
        }  
    }

        $eventbooking = new Eventbooking();
        $form = $this->createFormBuilder($eventbooking)
    
            ->add('dis', TextType::class,array(
                      'data' => ' ',
                      'label' =>'Title',
                  ))
            ->add('bookingstart', DateType::class, [
             "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime(),
             ])

            ->add('bookingtime', TimeType::class, [
                    'label' => 'Time',                    
                    'widget' => 'single_text',
                    'html5' => true,
                    
                    'with_seconds' => false,
                ])
            ->add('duration', ChoiceType::class,[
                      'choices'  => [
                        '1h' => '1',
                        '2h' => '2',
                        '3h' => '3',                  
                    ],
                  ])
            ->add('userid', HiddenType::class, [
                    'data' => $userid,
                ])
            ->add('usermail', HiddenType::class, [
                    'data' => $useremail,
                ])
            ->add('status', HiddenType::class, [
                    'data' => 'available',
                ])
            ->add('save', SubmitType::class, ['label' => 'booking'])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $eventbookingRepository->add($eventbooking, true);

            $this->addFlash('success', 'Thank you! Your booking is Submit!');
        }

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
            $jsonData = $request->request->all();
                
            foreach ($dates as $key => $va) {
                //print_r($va['time']);
                $my=$va['time'];
                //return new JsonResponse($va['time']);
                break;
            }

            foreach ($final as $key => $value) {
                if ($value['date'] == $jsonData['status']) {
                    $time = $value['time'];  
                } 
            }
            if(empty($time)){
                foreach ($dates as $key => $va) {
                    $time=$va['time'];
                    break;
                } 
            }  
            return new JsonResponse($time);          
        }
        // echo "<pre>";
        // print_r($final);
        // die();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'post' => $post,
            'basket'=>$basket,
            'eventbooking' => $eventbooking,
            'form' => $form->createView(),
            'final' => $final,
            'dates' => $dates,
            'mangerId'=>$mangerId,
        ]);
    }

    /**
     * @Route("/services", name="app_services")
     */
    public function servicesviwe(ServicesRepository $repo, CategoryRepository $rep, PostRepository $re, SessionInterface $session,ProductRepository $Product,Request $request , $limit = null, $offset = null): Response
    {
        $proid = $request->request->get('proid');
        if($proid){
            $pro= $proid;
        }else{
            $pro=1;
        }

        $totalpro = $Product->findBy(array());
        $total_count =count($totalpro);
        $count_per_page=10;

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
    
            $myLimit=10;
            $myOffset=0;
            if($page>1){
                $myOffset= $count_per_page * ($page-1); 
            }
            
            $pginateStartIndex = ($myOffset > 0) ? $myOffset : $myOffset;
        }else{
            $myLimit=10;
		    $pginateStartIndex=0;
        }

        $Pro = $Product->find($pro);
         $Services = $repo->findBy([]);
         $Product = $Product->findBy(array(),array('id' => 'ASC'),
         $myLimit,
         $pginateStartIndex);
         // print_r(gettype($Services));
         // foreach ($Services as $key => $value) {
         //    $ser['id']=$value->getId();
         //    $ser['servicesname']=$value->getServicesname();
         //    $ser['Thumbnail']=$value->getThumbnail();
         //    foreach($Product as $p){
         //            if($p->getService()->getServicesname() == $ser['servicesname'] ){
         //                print_r($p->getName());
         //            }
         //    }
         // }
            
        //$Category = $rep->search('V');
         //$Category = $rep->findBy([]);

        $search = $request->query->get('cat');
        if ($search) {
            $Category = $rep->search($search);
        } else {
            $Category = $rep->findBy([]);
        }
        
         
         
         $post = $re->findBy([]);
         $basket = $session->get('basket', []);
        return $this->render('home/services.html.twig', [
            'Services' => $Services,
            'Category' => $Category,
            'post' => $post,
            'basket'=>$basket,
            'Product'=>$Product,
            'Pro'=>$Pro,
            'total_pages' => $total_pages,
            
            
        ]);
    }

    /**
     * @Route("/prodetail/{id}")
     */
    public function productdetail($id,Request $request, ServicesRepository $repo,DocumentsforproductRepository $Doc, DocforproRepository $docforpro, ProductRepository $Product, SessionInterface $session): Response
    {
        $prodetail = $Product->find($id);
        $prodoc = $docforpro->findBy( array('proinfo' => $id), array('id' => 'DESC') );

        // foreach($prodoc as $pro){
        //     print_r(gettype($pro->getNewdocinfo()));
        //     foreach ($pro->getNewdocinfo() as $key => $value) {
        //         print_r($value->getName());
        //     }
        // }
        // die();

        $Servicesid = $prodetail->getService()->getid();
        
        //$Services = $Product->findBy(array('services_id' => $Servicesid));

        // add to basket handling
        $basket = $session->get('basket', []);

        if ($request->isMethod('POST')) {
            $basket[$prodetail->getId()] = $prodetail;
            $session->set('basket', $basket);
        }

        $isInBasket = array_key_exists($prodetail->getId(), $basket);
        
        return $this->render('home/prodetail.html.twig', [
            'controller_name' => 'HomeController',
            'prodetail' => $prodetail,
            'prodoc'=> $prodoc,
            'inBasket' => $isInBasket,
            // 'Services' => $Services,
        ]);
    }

    /**
     * @Route("/aboutus", name="app_about")
     */
    public function aboutus(Request $request): Response
    {
        
        return $this->render('home/aboutus.html.twig', [
            'controller_name' => 'HomeController',
            
        ]);
    }

    /**
     * @Route("/club", name="app_club")
     */
    public function club(Request $request): Response
    {
        
        return $this->render('home/club.html.twig', [
            'controller_name' => 'HomeController',
            
        ]);
    }

    /**
     * @Route("/affiliated", name="app_affiliated")
     */
    public function affiliated(Request $request): Response
    {
        
        return $this->render('home/affiliate.html.twig', [
            'controller_name' => 'HomeController',
            
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request): Response
    {
        
        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
            
        ]);
    }

    /**
     * @Route("/eventmangerboking", name="app_eventmangerboking", methods={"GET", "POST"})
     */
    public function eventmangerboking(Request $request, ManagerRegistry $doctrine,EventbookingRepository $eventbookingRepository, UserRepository $user): Response
    {
        $title = $request->request->get('title');
        $startdate = $request->request->get('startdate');
        $time = $request->request->get('time');
        $duration = $request->request->get('duration');
        $userid = $request->request->get('userid');
        $useremail = $request->request->get('useremail');
        $status = $request->request->get('status');
        $mangerId = $request->request->get('mangerid');
        $manger = $doctrine->getRepository(User::class)->find($mangerId);

        $date= \DateTime::createFromFormat('d/m/Y',date("d/m/Y", strtotime($startdate)));
        $time= \DateTime::createFromFormat('h:i:sa',date("h:i:sa", strtotime($time)));
        
        $entityManager =$this->getDoctrine()->getManager();
        $Eventbooking = new Eventbooking;
        $Eventbooking->setDis($title);
        $Eventbooking->setBookingstart($date);
        $Eventbooking->setBookingtime($time);
        $Eventbooking->setDuration($duration);
        $Eventbooking->setUserid($userid);
        $Eventbooking->setUsermail($useremail);
        $Eventbooking->setManger($manger);
        $Eventbooking->setStatus($status);
        
        
        $entityManager->persist($Eventbooking);
        $entityManager->flush();

        $this->addFlash('success', 'Thank you! appointment book successfully');
    }
}
