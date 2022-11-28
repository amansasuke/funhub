<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Time;

use App\Repository\AssignGroupRepository;
use App\Repository\AssignGroupUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\AssignGroup;
use App\Entity\AssignGroupUser;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\OrderdocRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Entity\Eventbooking;
use App\Form\EventbookingType;
use App\Repository\EventbookingRepository;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;

use App\Entity\Mangereventbooking;
use App\Repository\MangereventbookingRepository;


class MangerController extends AbstractController
{
    /**
     * @Route("/manger", name="app_manger")
     */
    public function index(ManagerRegistry $doctrine,AssignGroupRepository $assignGroup, AssignGroupUserRepository $assignUser, UserRepository $userR): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $assignGroup = $assignGroup->findBy([]);

        foreach($assignGroup as $assign){
            foreach($assign->getUser() as $users){
                if($users->getId() == $user->getId()){
                    $mangerId = $users->getId();
                    $groupId = $assign->getId();
                }
            }
        }
        $AssignGroupM = $doctrine->getRepository(AssignGroup::class)->findBy(
            ['id' => $groupId]
        );
        $mangerId =[];
        foreach($AssignGroupM as $AssignM){
            foreach($AssignM->getUser() as $users){
                if($users->getId() != $user->getId()){
                    $mangerId[] = $users->getId();
                }
            }           
        }

        $us =[];
        foreach ($mangerId as $key => $manger) {

            $us[] = $userR->find($manger);
            
        }

        return $this->render('manger/index.html.twig', [
            'users' => $us,
        ]);
    }

    /**
     * @Route("/userorder/{id}", name="app_userorder")
     */
    public function userorder($id ,ManagerRegistry $doctrine,AssignGroupRepository $assignGroup, AssignGroupUserRepository $assignUser, UserRepository $userR): Response
    {
        $user = $userR->find($id);
       $email = $user->getEmail();
        $order = $doctrine->getRepository(Order::class)->findBy(
            ['email' => $email]
        );

        
        return $this->render('manger/userorder.html.twig', [
            'orders' => $order,
        ]);
    }

    /**
     * @Route("/assignuser/{id}")
     */
    public function editorderstatus($id,ManagerRegistry $doctrine, Request $request, OrderdocRepository $Orderdoc, UserRepository $userR): Response
    {
        $Orderd = $doctrine->getRepository(Order::class)->find($id);
        //$order = new Order;

        $form = $this->createFormBuilder($Orderd)
            
            ->add('user', EntityType::class,array(
                      'class' => User::class,
                      'multiple' => true,
                      'label'=>'',                      
                  ),
        )
        
            ->add('save', SubmitType::class, ['label' => 'assign order to agent'])
            ->getForm();
            


            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //$order = $form->getData();

                // $user1 = $userR->find(12);

                // $Orderd->getUser()->add($user1);


                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($Orderd);

                $entityManager->flush();


                return $this->render('manger/assignuser.html.twig', [
                  'form' =>$form->createView(),
                ]);
            }

        return $this->render('manger/assignuser.html.twig', [
          'form' =>$form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/setorderdate", name="app_setorderdate", methods={"GET", "POST"})
     */
    public function setorderdate($id ,Request $request,ManagerRegistry $doctrine): Response
    {
        $startdate = $request->request->get('startdate');
        
        $startdate= \DateTime::createFromFormat('d/m/Y',date("d/m/Y", strtotime($startdate)));

        $entityManager =$this->getDoctrine()->getManager();
        $Orderd = $doctrine->getRepository(Order::class)->find($id);
        $Orderd->setStartdate($startdate);
        
        $entityManager->persist($Orderd);
        $entityManager->flush();

        return new JsonResponse(array('statsu' => true, 'messages' => array('done')));
    }

    /**
     * @Route("/{id}/setendorderdate", name="app_setendorderdate", methods={"GET", "POST"})
     */
    public function edit($id ,Request $request,ManagerRegistry $doctrine): Response
    {
        $enddate = $request->request->get('enddate');
        
        $enddate= \DateTime::createFromFormat('d/m/Y',date("d/m/Y", strtotime($enddate)));

        $entityManager =$this->getDoctrine()->getManager();
        $Orderd = $doctrine->getRepository(Order::class)->find($id);
        $Orderd->setEnddate($enddate);
        
        $entityManager->persist($Orderd);
        $entityManager->flush();

        return new JsonResponse(array('statsu' => true, 'messages' => array('done')));
    }

    /**
     * @Route("/mangerbooking", name="app_mangerbooking")
     */
    public function booking(ManagerRegistry $doctrine,EventbookingRepository $eventbookingRepository, AppointmentRepository $appointmentRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userid= $user->getId();
        $eventbooking = $eventbookingRepository->findBy(array('manger'=>$user->getId()));

        $appointment = $appointmentRepository->findBy(array('MangerID'=>$userid));

        $booking= [];
        $i=0;
        foreach ($eventbooking as $key => $value) {
            $booking[$i]['title']= $value->getDis();
            $booking[$i]['date']= $value->getBookingstart();
            $booking[$i]['time']= $value->getBookingtime();
            $booking[$i]['meeting']= $value->getMeetinglink();

            $i++;
        }

        $orderbooking= [];
        $j = 0;
        foreach ($appointment as $key => $v) {
            $orderbooking[$j]['title']= 'order meeting';
            $orderbooking[$j]['date']= $v->getClientStartDate();
            $orderbooking[$j]['enddate']= $v->getClientEndTime();            
            $orderbooking[$j]['time']= $v->getClientStartTime();
            $j++;
        }
         $today = date("Y/m/d");
        return $this->render('manger/booking.html.twig', [
            'bookings' => $booking,
            'orderbooking' => $orderbooking,
            'today'=>$today
        ]);
    }

    /**
     * @Route("/mangerassignbooking", name="app_mangerassignbooking")
     */
    public function assignbooking(ManagerRegistry $doctrine,EventbookingRepository $eventbookingRepository, AppointmentRepository $appointmentRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userid= $user->getId();
        $eventbooking = $eventbookingRepository->findBy(array('manger'=>$user->getId()));

         $today = date("Y/m/d");
        return $this->render('manger/assignbooking.html.twig', [
            'bookings' => $eventbooking,
            'today'=>$today
        ]);
    }

    /**
     * @Route("/mangerevent", name="app_mangerevent")
     */
    public function mangerevent(Request $request, MangereventbookingRepository $MangereventbookingRepository): Response
    {
        $manger = $this->get('security.token_storage')->getToken()->getUser();
        
         return $this->render('manger/mangerevent.html.twig', [
            'Mangereventbooking' => $MangereventbookingRepository->findBy(array('mangerid'=>$manger->getId())),
        ]);
    }

    /**
     * @Route("/updatemeeting/{id}", name="app_updatemeeting", methods={"GET", "POST"})
     */
    public function mangerupdatemeeting($id, Request $request, MangereventbookingRepository $MangereventbookingRepository,EventbookingRepository $eventbookingRepository, ManagerRegistry $doctrine): Response
    {   
        $manger = $this->get('security.token_storage')->getToken()->getUser();
        $event = $eventbookingRepository->find($id);
        $meetinglink = $request->request->get('meeting');


        $entityManager =$this->getDoctrine()->getManager();
        //$event  = $doctrine->getRepository(Appointment::class)->find($id);
        $event->setMeetinglink($meetinglink);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->render('manger/mangerevent.html.twig', [
            'Mangereventbooking' => $MangereventbookingRepository->findBy(array('mangerid'=>$manger->getId())),
        ]);
    }


    /**
     * @Route("/mangerevent/new", name="app_mangereventnew")
     */
    public function mangereventnew(Request $request, MangereventbookingRepository $MangereventbookingRepository): Response
    {
        $manger = $this->get('security.token_storage')->getToken()->getUser();
        $mangerid  = $manger->getId();

        $Mangereventbooking = new Mangereventbooking();
        $form = $this->createFormBuilder($Mangereventbooking)
            ->add('title', TextType::class,array(
                      'label' => 'Title',
                  ))
            ->add('StartDate', DateType::class, [
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime(),
                'attr' => [
                'class' => 'form-control input-md datepicker',
                ]
                    
                ])
        ->add('EndDate', DateType::class, [
                "widget" => 'single_text',
                "format" => 'yyyy-MM-dd',
                "data" => new \DateTime(),
                'attr' => [
                'class' => 'form-control input-md datepicker',
                ]
            ])
        ->add('StartTime', TimeType::class, [
                'label' => 'Start Time',                    
                'widget' => 'single_text',
                'html5' => true,
                
                'with_seconds' => false,
            ])
        ->add('EndTime', TimeType::class, [
            'label' => 'End Time',                    
            'widget' => 'single_text',
            'html5' => true,
            
            'with_seconds' => false,
            ])
        ->add('mangerid', HiddenType::class, [
                    'data' => $mangerid,
                ])
        ->add('status', HiddenType::class, [
                    'data' => 0,
                ])
        // ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $MangereventbookingRepository->add($Mangereventbooking, true);

            return $this->redirectToRoute('app_mangerevent', [], Response::HTTP_SEE_OTHER);
        }
        
         return $this->render('manger/mangereventnew.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

