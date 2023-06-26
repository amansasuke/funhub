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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use App\Entity\Mangereventbooking;
use App\Repository\MangereventbookingRepository;

use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Feedback;
use App\Repository\FeedbackRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $re ,  SessionInterface $session, EventbookingRepository $eventbookingRepository, Request $request, MangereventbookingRepository $MangereventbookingRepository): Response
    {
        $post = $re->postlimt();

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
                      'label' =>'Add your query',
                      'attr' => ['placeholder' => 'Enter Your Title *'],
                      
                  ))
            ->add('bookingstart', DateType::class, [
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime(),
                'label' =>'Booking Date',
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d'),
                ],
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
            ->add('save', SubmitType::class, ['label' => 'Confirm'])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $eventbookingRepository->add($eventbooking, true);

            //$this->addFlash('success', 'Thank you! Your booking is Submit!');
            flash()->addSuccess('Thank you! Appointment request submitted successfully');
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
        $count_per_page=9;

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
    
            $myLimit=9;
            $myOffset=0;
            if($page>1){
                $myOffset= $count_per_page * ($page-1); 
            }
            
            $pginateStartIndex = ($myOffset > 0) ? $myOffset : $myOffset;
        }else{
            $myLimit=9;
		    $pginateStartIndex=0;
        }

        $Pro = $Product->find($pro);
         $Services = $repo->findBy([]);
         
        if (isset($_GET['tags'])) {
            
            $Productshow = $Product->searchtags($_GET['tags'], $_GET['cat']);
        }else{
            $Productshow = $Product->findBy(array(),array('bgcolor' => 'ASC'),
            $myLimit,
            $pginateStartIndex);
        }
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
         $Category = $rep->findBy([]);

        // $search = $request->query->get('cat');
        // if ($search) {
        //     $Category = $rep->search($search);
        // } else {
        //     $Category = $rep->findBy([]);
        // }
        $basket = $session->get('basket', []);
        if ($request->isMethod('POST')) {
            $id = $request->request->get('cart');
            $prodetail = $Product->find($id);
            $basket[$prodetail->getId()] = $prodetail;
            $session->set('basket', $basket);
            //$this->addFlash('success', 'Thank you! Successfully added to cart !');
            flash()->addSuccess('Thank you! Successfully added to cart !');
        }
         
        $post = $re->findBy([]);
        $proget=[];
        $i=0;
        foreach ($Productshow as $key => $proshow) {
            $proget[$i]['id'] = $proshow->getId();
            $proget[$i]['bgcolor'] = $proshow->getBgcolor();
            $proget[$i]['description'] = $proshow->getDescription();
            $proget[$i]['name'] = $proshow->getName();
            $proget[$i]['regularprice'] = $proshow->getRegularprice();
            $proget[$i]['price'] = $proshow->getPrice();
            $proget[$i]['cart'] = array_key_exists($proshow->getId(), $basket);
            $i++;
        }
        
        // echo"<pre>";
        // print_r($proget);
        // die;
         
        return $this->render('home/services.html.twig', [
            'Services' => $Services,
            'Category' => $Services,
            'post' => $post,
            'basket'=>$basket,
            'Product'=>$proget,
            'Pro'=>$Pro,
            'total_pages' => $total_pages,
            
            
        ]);
    }

    /**
     * @Route("/prodetail/{id}", name="app_prodetail" )
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

        //$Servicesid = $prodetail->getService()->getid();
        
        //$Services = $Product->findBy(array('services_id' => $Servicesid));

        // add to basket handling
        $basket = $session->get('basket', []);

        if ($request->isMethod('POST')) {
            $basket[$prodetail->getId()] = $prodetail;
            $session->set('basket', $basket);
            //$this->addFlash('success', 'Thank you! Successfully added to cart !');
            flash()->addSuccess('Thank you! Successfully added to cart !');
        }

        $isInBasket = array_key_exists($prodetail->getId(), $basket);
        $basket = $session->get('basket', []);

        $Productshow = $Product->searchtags($prodetail->getName(), 5, 0 );

        return $this->render('home/prodetail.html.twig', [
            'controller_name' => 'HomeController',
            'prodetail' => $prodetail,
            'prodoc'=> $prodoc,
            'inBasket' => $isInBasket,
            'basket' => $basket,
            'Productshow' => $Productshow,
            'proid'       => $id,
        ]);
    }

    /**
     * @Route("/addtocart", name="app_addtocart", methods={"GET", "POST"})
     */
    public function addtocart(Request $request, SessionInterface $session,ProductRepository $Product): Response
    {   
        $addoncart = $request->request->get('addoncart'); 
        $proid = $request->request->get('proid');

        $prodetail = $Product->find($addoncart);

        $basket = $session->get('basket', []);
        $basket[$prodetail->getId()] = $prodetail;
        $session->set('basket', $basket);

        //$this->addFlash('success', 'Thank you! Successfully added to cart !');
        flash()->addSuccess('Thank you! Successfully added to cart !');
        return $this->redirectToRoute('app_prodetail',['id' => $proid], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/buynow", name="app_buynow", methods={"GET", "POST"})
     */
    public function buynow(Request $request, SessionInterface $session,ProductRepository $Product): Response
    {   
        $addoncart = $request->request->get('addoncart'); 
        $proid = $request->request->get('proid');

        $prodetail = $Product->find($addoncart);

        $basket = $session->get('basket', []);
        $basket[$prodetail->getId()] = $prodetail;
        $session->set('basket', $basket);

        //$this->addFlash('success', 'Thank you! Successfully added to cart !');
        //flash()->addSuccess('Thank you! Successfully added to cart !');
        return $this->redirectToRoute('app_checkout');
    }

    /**
     * @Route("/aboutus", name="app_about")
     */
    public function aboutus(Request $request, SessionInterface $session): Response
    {
        $basket = $session->get('basket', []);
        return $this->render('home/aboutus.html.twig', [
            'controller_name' => 'HomeController',
            'basket' => $basket,
            
        ]);
    }

    /**
     * @Route("/privacy", name="app_privacy")
     */
    public function privacy(Request $request, SessionInterface $session): Response
    {
        $basket = $session->get('basket', []);
        return $this->render('home/privacy.html.twig', [
            'controller_name' => 'HomeController',
            'basket' => $basket,
            
        ]);
    }

     /**
     * @Route("/refund ", name="app_refund")
     */
    public function refund(Request $request, SessionInterface $session): Response
    {
        $basket = $session->get('basket', []);
        return $this->render('home/refund.html.twig', [
            'controller_name' => 'HomeController',
            'basket' => $basket,
            
        ]);
    }

    /**
     * @Route("/terms", name="app_terms")
     */
    public function terms(Request $request, SessionInterface $session): Response
    {
        $basket = $session->get('basket', []);
        return $this->render('home/terms.html.twig', [
            'controller_name' => 'HomeController',
            'basket' => $basket,
            
        ]);
    }


    /**
     * @Route("/club", name="app_club")
     */
    public function club(Request $request, SessionInterface $session): Response
    {
        $basket = $session->get('basket', []);
        return $this->render('home/club.html.twig', [
            'controller_name' => 'HomeController',
            'basket' => $basket,
        ]);
    }

    /**
     * @Route("/affiliated", name="app_affiliated")
     */
    public function affiliated(Request $request, SessionInterface $session): Response
    {
        $basket = $session->get('basket', []);
        return $this->render('home/affiliate.html.twig', [
            'controller_name' => 'HomeController',
            'basket'=>$basket,
            
        ]);
    }

    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(Request $request, SessionInterface $session,  MailerInterface $mailer): Response
    {
        $basket = $session->get('basket', []);

        if ($request->isMethod('POST')) {
            $name = $request->request->get('name');
            $phone = $request->request->get('phone');
            $email = $request->request->get('email');
            $message = $request->request->get('message');
            

            $email = (new TemplatedEmail())
                ->from($email)
                ->to(new Address('contact@thefinanzi.com'))
                ->subject('contact message')
                ->htmlTemplate('emails/contactmail.html.twig')
                ->context(['name' => $name, 'phone' => $phone,'emailid' => $email,'msg'=>$message ]);
            $mailer->send($email);


            //$this->addFlash('success', 'Thank you! Query Message sent successfully');
            flash()->addSuccess('Thank you! Query Message sent successfully');
            return $this->redirectToRoute("app_contact");
        }

        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController',
            'basket'=>$basket,
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

        //$this->addFlash('success', 'Thank you! appointment book successfully');
        flash()->addSuccess('Thank you! appointment book successfully');
        return $this->redirectToRoute("app_mybooking");
    }

     /**
     * @Route("/feedback", name="app_feedback")
     */
    public function feedback(Request $request, SessionInterface $session, FeedbackRepository $FeedbackRepository, UserRepository $user,  ManagerRegistry $doctrine): Response
    {
        $basket = $session->get('basket', []);

        $Feedback = $FeedbackRepository->findBy(array(),array('id' => 'DESC'));

        $Feed =[];
        $i= 0;
        foreach ($Feedback as $key => $value) {
            $Feed[$i]['disreviwe'] = $value->getDisreviwe();
            $Feed[$i]['reating'] = $value->getReating();
            $Feed[$i]['proname'] = $value->getProname();
            $userdat = $doctrine->getRepository(User::class)->find($value->getUserid());
            if (!empty($userdat)) {
                $Feed[$i]['username'] = $userdat->getName();
                $Feed[$i]['icon'] = $userdat->getImgicon();
            }else{
                $Feed[$i]['username'] ="";
                $Feed[$i]['icon'] = "";
            }
            

            $i++;
        }
        
        return $this->render('home/feedback.html.twig', [
            'controller_name' => 'HomeController',
            'basket' => $basket,
            'Feedback' =>$Feed,
        ]);
    }

    /**
     * @Route("/index1", name="app_index1")
     */
    public function index1(PostRepository $re ,  SessionInterface $session, EventbookingRepository $eventbookingRepository, Request $request, MangereventbookingRepository $MangereventbookingRepository): Response
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
                      'label' =>false,
                      'attr' => ['placeholder' => 'Enter Your Title *'],
                      
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

            //$this->addFlash('success', 'Thank you! Your booking is Submit!');
            flash()->addSuccess('Thank you! Your booking is Submit!');
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
        
        return $this->render('home/index1.html.twig', [
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
}
