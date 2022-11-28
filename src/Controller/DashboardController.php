<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Docofpro;
use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\DocumentsforproductRepository;
use App\Repository\OrderdocRepository;
use App\Entity\Orderdoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;


use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Time;
use App\Form\DocForClientType;
use App\Repository\DocForClientRepository;
use App\Entity\Voucher;
use App\Entity\User;
use App\Entity\Vouchercode;
use App\Entity\Uservoucher;
use App\Repository\VouchercodeRepository;
use App\Repository\VoucherRepository;
use App\Repository\UservoucherRepository;
use App\Repository\UserRepository;
use App\Entity\Eventbooking;
use App\Repository\EventbookingRepository;

use App\Entity\Mangereventbooking;
use App\Repository\MangereventbookingRepository;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     * 
     * @IsGranted("ROLE_USER")
     */
    public function index(ManagerRegistry $doctrine,AppointmentRepository $appointmentRepository, DocumentsforproductRepository $doc,OrderdocRepository $od, DocForClientRepository $docForClientRepository): Response
    {   
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();

        $sunmitdoc =[];
        $Orderdoc = $od->findBy([]);
        // sumbit documents for order
        $i=0;
        foreach($Orderdoc as $Ordoc){
            foreach($Ordoc->getOrderid() as $Oroc){
               $sunmitdoc[$i]['docname'] = $Ordoc->getDocname();
               $sunmitdoc[$i]['orderid'] = $Oroc->getId();
               $sunmitdoc[$i]['remark'] = $Ordoc->getRemark();
               $sunmitdoc[$i]['status'] = $Ordoc->getStatus();
               $i++;
            }
        }

        $doc = $doc->findBy([]);

        $order = $doctrine->getRepository(Order::class)->findBy(
            ['email' => $user->getEmail()]
        );

        // foreach($order as $orderid){
        //     print_r($orderid->getId());
        //     print_r($orderid->getProducts()->getId());      
        //     echo "<br>";
        // }
        // doc requerd for order
        $rqedoc =[];
        $j=0;
        foreach($doc as $dof){
            $rqedoc[$j]['prodid']= $dof->getProductinfo()->getId();
            $rqedoc[$j]['docinfo']= $dof->getDocinfo()->getName();
            $j++;
        }

        $orderin = [];
        $k=0;
        foreach($order as $o){
                $product = $o->getProducts();
            foreach($product as $pro){
                $orderin[$k]['proid'] = $pro->getId();
                $orderin[$k]['orderid'] = $o->getId();
                $k++;
            }
        }
        $submitorderdoc = [];
        $requrdoc= [];
        $l=0;
        $a=0;
        foreach ($orderin as $key => $orde) {
            foreach($rqedoc as $docre){
                if($orde['proid'] === $docre['prodid'] ){
                    $requrdoc[$a]['orderid'] = $orde['orderid'];
                    $requrdoc[$a]['requrdoc'] = $docre['docinfo'];
                    foreach ($sunmitdoc as $key => $submited) {
                        if($orde['orderid'] === $submited['orderid'] && $submited['docname'] == $docre['docinfo']){
                            $submitorderdoc[$l]['orderid'] = $orde['orderid'];
                            $submitorderdoc[$l]['submitdoc'] = $submited['docname'];
                            $submitorderdoc[$l]['remark'] = $submited['remark'];
                            $submitorderdoc[$l]['status'] = $submited['status'];
                            
                            $l++;
                        }
                    } 
                    $a++;
                }
            }

        }
        
        $finalredoc = [];
        $finalredocsub = [];
        $v = 0;
        $u=0;
        //print_r($requrdoc);
        foreach ($requrdoc as $key => $requrd) {
            foreach($submitorderdoc as $submitorder){
                if($requrd['orderid'] == $submitorder['orderid'] ){
                    if($requrd['requrdoc'] != $submitorder['submitdoc'] ){
                        $finalredoc[$v]['req'] = $requrd['requrdoc'];
                        $finalredoc[$v]['orderid'] = $requrd['orderid'];
                    }
                    if($requrd['requrdoc'] == $submitorder['submitdoc']){
                        $finalredocsub[$u]['orderid'] = $requrd['orderid'];
                        $finalredocsub[$u]['submitdoc'] = $requrd['requrdoc'];
                        $finalredocsub[$u]['remark'] = $submitorder['remark'];
                         $finalredocsub[$u]['status'] = $submitorder['status'];
                  
                    }
                $v++;
                $u++;
                }
            }
        }

       // echo "<pre>";
        $rqdoc = [];
        $z = 0;
        foreach($requrdoc as $rdoc){
            foreach($finalredocsub as $fsub){
                if($fsub['orderid'] == $rdoc['orderid'] && $fsub['submitdoc'] == $rdoc['requrdoc']    ){
                   $rqdoc[$z]['orderid'] = $rdoc['orderid']; 

                   $rqdoc[$z]['submitdoc'] = $rdoc['requrdoc']; 
                    }
                 $z++;
            }
        }

        foreach($requrdoc as $rdoc){
            foreach($rqdoc as $rqd){
                if($rqd['orderid'] == $rdoc['orderid'] && $rqd['submitdoc'] != $rdoc['requrdoc']    ){
                   // $rqdoc[$z]['orderid'] = $rdoc['orderid']; 
                    //print_r($rdoc['requrdoc']);echo '<br>';
                   // $rqdoc[$z]['submitdoc'] = $rdoc['requrdoc']; 
                    }
                 $z++;
            }
        }


        $arry = [];
        $arry1 = [];
        $o = 0;
        foreach ($orderin as $key => $orde) {
             $arry[$o]['orderid'] = $orde['orderid'];
            foreach($rqedoc as $docre){
                if($orde['proid'] === $docre['prodid'] ){
                    $arry1[] = $docre['docinfo'];
                }
              $arry[$o]['docname']= $arry1;    
            }
            $arry1 = (array) null;
            $o++;
        }

        $subdoc = [];
        $subdoc1 = [];
        $l=0;
        $a=0;
        foreach ($orderin as $key => $orde) {
            foreach($rqedoc as $docre){
                if($orde['proid'] === $docre['prodid'] ){
                   $subdoc[$l]['orderid'] = $orde['orderid'];
                    foreach ($sunmitdoc as $key => $submited) {
                        if($orde['orderid'] === $submited['orderid'] && $submited['docname'] == $docre['docinfo']){
                            $subdoc1[] = $submited['docname'];
                        }                       
                    }                                           
                }
            }
            $subdoc[$l]['docname'] = $subdoc1;
            $subdoc1 = (array) null;
            $l++;
        }

        // echo "sub doc erray ============";
        // foreach($arry as $rdoc){ 
        //     foreach($subdoc as $subdo){
        //         $ad[] =  array_diff($rdoc['docname'],$subdo['docname']) ;      
        //     }
        // }
        // print_r($ad);
        // foreach ($rdoc as $key => $rd) {
        //     foreach ($ad as $key => $a) 
        //         print_r($a);

        //  }
        // }
//$fullDiff = array_merge(array_diff($subdoc, $arry), array_diff($arry, $subdoc));

    // foreach ($subdoc as $key => $value1) {
    //     foreach($value1['docname'] as $rdoc1){
    //         foreach ($arry as $key => $value) {
    //     foreach($value['docname'] as $rdoc){
    //         print_r($rdoc1);echo"<br>";
    //     }}
    //     }
    // }
        
        // print_r( $arry);
        // echo "sub doc erray ============";
        // print_r( $subdoc);
        
        
         //die();
        $appointment = $appointmentRepository->findBy(array('ClientId'=>$user->getId()));

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/index.html.twig', [
            'order'=>$order,
            'doc'=> $doc,
            'Orderdoc'=>$Orderdoc,
            'finalredoc'=>$requrdoc,
            'finalredocsub'=>$finalredocsub,
            'appointments'=>$appointment,
            'doc_for_clients' => $docForClientRepository->findAll(),

        ]);
    }


     /**
     * @Route("/userprofile", name="app_profile")
     */
    public function userprofile(Request $request,SluggerInterface $slugger): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if(empty($user->getImgicon())){
        $form = $this->createFormBuilder($user)
            ->add('imgicon', FileType::class,array(
                      'label' => ' ',
                  ))
            
            ->add('save', SubmitType::class, ['label' => 'Upload'])
            ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $brochureFile = $form->get('imgicon')->getData();            
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('profile_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
        
                    }

                     $entityManager = $this->getDoctrine()->getManager();
                    $user->setImgicon($newFilename);
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
                $this->addFlash('success', 'Thank you! profile pic update successfully');
            }
        }else{
            $form = $this->createFormBuilder($user)
            ->add('imgicon', TextType::class,array(
                      'label' => ' ',
                  ))
            
            ->add('save', SubmitType::class, ['label' => 'Upload'])
            ->getForm();
            $form->handleRequest($request);
        }

        $UserCategory =  $user->getUserCategory();
        $phoneno =  $user->getPhoneNo();
        $panno =  $user->getPanNo();
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/userprofile.html.twig', [
            'user' => $user,
            'Category'=>$UserCategory,
            'PanNo'=>$panno,
            'phoneno'=>$phoneno,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/submit", name="app_dashboard_submit", methods={"GET", "POST"})
     */
    public function edit($id ,Request $request,ManagerRegistry $doctrine, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {
        $startdate = $request->request->get('startdate');
        $starttime = $request->request->get('starttime');
        $endtime = $request->request->get('endtime');
        $Status = $request->request->get('Status');
        
        $startdate= \DateTime::createFromFormat('d/m/Y',date("d/m/Y", strtotime($startdate)));
        $starttime= \DateTime::createFromFormat('h:i:sa',date("h:i:sa", strtotime($starttime)));
        $endtime= \DateTime::createFromFormat('h:i:sa',date("h:i:sa", strtotime($endtime)));
        // print_r($starttime);
        // print_r($endtime);
        // die;
        
        $entityManager =$this->getDoctrine()->getManager();
        $appointment  = $doctrine->getRepository(Appointment::class)->find($id);
        $appointment->setClientStartDate($startdate);
        $appointment->setClientStartTime($starttime);
        $appointment->setClientEndTime($endtime);
        $appointment->setClientStatus($Status);
        
        $entityManager->persist($appointment);
        $entityManager->flush();

        $this->addFlash('success', 'Thank you! appointment book successfully');
    }


     /**
     * @Route("/mywallet", name="app_mywallet")
     */
    public function mywallet(): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $UserCategory =  $user->getUserCategory();
        $phoneno =  $user->getPhoneNo();
        $panno =  $user->getPanNo();
        $Wellet =  $user->getWellet();
        // echo"<pre>";
        // print_r($user->getUserCategory());
        // die;
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/mywallet.html.twig', [
            'user' => $user,
            'Category'=>$UserCategory,
            'Wellet'=>$Wellet,
            'phoneno'=>$phoneno,
        ]);
    }

    /**
     * @Route("/voucher", name="app_voucher")
     */
    public function voucher(VouchercodeRepository $Vouchercode,UservoucherRepository $uservoucherdata, VoucherRepository $Voucher,Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userdat = $doctrine->getRepository(User::class)->find($user->getId());
        $Vouchercode = $Vouchercode->findBy([]);
        $Voucher = $Voucher->findBy([]);
        $uservoucherdata = $uservoucherdata->findBy([]);
        $Uservoucher = new Uservoucher();

        $usvouch = $doctrine->getRepository(Vouchercode::class)->findBy(array('User'=>$user->getId()));

        $voucherrelation=[];
        $i=0;
        foreach ($usvouch as $key => $usvo) {
            $voucherrelation[$i]['VouchercodeId'] = $usvo->getId();
            $voucherrelation[$i]['VoucherId'] = $usvo->getV()->getId();
            $i++;   
        }
    

        $codedata=[];
        $i=0;
        foreach ($Vouchercode as $key => $vo) {             
            if($vo->getUser() == $user){
                $codedata[$i]['user']= $user->getId(); 
                $codedata[$i]['code'] = $vo->getCode();
                $codedata[$i]['voucher'] = $vo->getV()->getId();
                $codedata[$i]['vouchername'] = $vo->getV()->getName();
                $codedata[$i]['voucherPrices'] = $vo->getV()->getPrices();
                $codedata[$i]['vouchericon'] = $vo->getV()->getVouchericon();
                $codedata[$i]['redeem']= 1;
                
            }
            $i++;
        }


        if ($request->isMethod('POST')) {
            $voucherprice = $request->request->get('voucherprice');
            $voucherid = $request->request->get('voucherid');
            $userweal =$user->getWellet();
            $newwal= $userweal-$voucherprice; 
           
            foreach ($Vouchercode as $key => $value) {
                if ($value->isStatus() == 0 && $value->getUser()=="" && $value->getV()->getId() == $voucherid)
                {
                    $VouchercodeId = $value->getId();
                    break;
                }   
            }
            $Vouchercodes = $doctrine->getRepository(Vouchercode::class)->find($VouchercodeId);
            $Voucher = $doctrine->getRepository(Voucher::class)->find($voucherid);
            
            

            $entityManager = $this->getDoctrine()->getManager();
            // $Uservoucher->getVoucher()->add($Voucher);
            // $Uservoucher->getUsers()->add($userdat);

            $userdat->setWellet($newwal);
            $entityManager->persist($userdat);
            $Vouchercodes->setUser($userdat);
            $entityManager->persist($Vouchercodes);
            //$entityManager->persist($Uservoucher);
            $entityManager->flush();

            //$Voucher = $Voucher->findBy([]);
        }
        
        
        $UserCategory =  $user->getUserCategory();
        $phoneno =  $user->getPhoneNo();
        $panno =  $user->getPanNo();
        $Wellet =  $user->getWellet();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('dashboard/vouchers.html.twig', [
            'user' => $user,
            'Category'=>$UserCategory,
            'Wellet'=>$Wellet,
            'phoneno'=>$phoneno,
            'Vouchercode'=>$Vouchercode,
            'Voucher'=>$Voucher,
            'codedata'=>$codedata,
            'voucherrelation'=>$voucherrelation

        ]);
    }

     /**
     * @Route("/onemonth", name="app_onemonth")
     */
    public function onemonth(ManagerRegistry $doctrine,UserRepository $user): Response
    {
        $user = $user->findBy([]);

        foreach ($user as $key => $u) {
           
            if ($u->getWellet() != 0) {
                $userid = $u->getId();
                $wallet = $u->getWellet();

                $percentage =1;
                $new_wallet = ($percentage / 100) * $wallet;
                $totalwallet = $new_wallet+$wallet;
                $totalwallet =  round($totalwallet);
                
                $Userdata = $doctrine->getRepository(User::class)->find($userid);
                
                $entityManager = $this->getDoctrine()->getManager();
                $Userdata->setWellet($totalwallet);
                $entityManager->persist($Userdata);
                $entityManager->flush();
            }
            
        }
    }


    /**
     * @Route("/upload", name="app_upload")
     */
    public function upload(Request $request, SluggerInterface $slugger): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
        $form = $this->createFormBuilder($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();
            print_r($brochureFile);
            die();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setBrochureFilename($newFilename);
            }

            // ... persist the $product variable or any other work

            $this->addFlash('success', 'Thank you! Your booking is Submit!');
        }

        return $this->renderForm('product/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/test", name="app_test")
     */
    public function test(Request $request): Response
    {
        
        return $this->render('dashboard/test.html.twig', [
            'controller_name' => 'HomeController',
            
        ]);
    }

    /**
     * @Route("/mybooking", name="app_mybooking")
     */
    public function mybooking(Request $request, EventbookingRepository $Event, AppointmentRepository $appointmentRepository, MangereventbookingRepository $MangereventbookingRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userid = $user->getId();
        $event = $Event->findBy(array('userid'=>$userid));

        $appointment = $appointmentRepository->findBy(array('ClientId'=>$userid));

        $orderbooking= [];
        $j = 0;
        foreach ($appointment as $key => $v) {
            $orderbooking[$j]['title']= 'order meeting';
            $orderbooking[$j]['date']= $v->getClientStartDate();
            $orderbooking[$j]['enddate']= $v->getClientEndTime();            
            $orderbooking[$j]['time']= $v->getClientStartTime();
            $j++;
        }

        $booking= [];
        $i=0;
        foreach ($event as $key => $value) {
            $booking[$i]['title']= $value->getDis();
            $booking[$i]['date']= $value->getBookingstart();
            $booking[$i]['time']= $value->getBookingtime();
            $booking[$i]['meeting']= $value->getMeetinglink();

            $i++;
        }
         $today = date("Y/m/d");

        if (TRUE === $this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userid= $user->getId();
        $useremail= $user->getEmail();
        }else{
            $useremail= '';
            $userid= '';
        }
        $mangerId='';
        //$even = $Event->findBy(array('userid' => $userid));
        foreach( $event as $ev){
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
        $mangereventdata = $Event->findBy(array('manger' => $mangerId));
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
        // echo "<pre>";
        // print_r($dates);
        // die;
        $final=[];
        $j=0;
        foreach ($eventbookingTime as $key => $value) {
            foreach ($dates as $key => $va) {
                if ($value['date'] == $va['date']) {
                    $result=array_diff($va['time'],$value['time']);
                    $final[$j]['date']= $va['date'];
                    $final[$j]['time']= $result;
                }else{
                    $final[$j]['date']= $va['date'];
                    $final[$j]['time']= $va['time'];
                }
                $j++;
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
            $Event->add($eventbooking, true);

            $this->addFlash('success', 'Thank you! Your booking is Submit!');
        }
        
        return $this->render('dashboard/mybooking.html.twig', [
            'bookings' => $booking,
            'orderbooking' => $orderbooking,
            'today' => $today,
            'form' => $form->createView(),
            'final' => $final,
            'dates' => $dates,
            'mangerId'=>$mangerId,
            
        ]);
    }
}
