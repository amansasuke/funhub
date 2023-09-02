<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


use App\Repository\AssignGroupRepository;
use App\Repository\AssignGroupUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\AssignGroup;
use App\Entity\AssignGroupUser;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\OrderdocRepository;
use App\Entity\Order;

/**
 * @Route("/appointment")
 */
class AppointmentController extends AbstractController
{
    /**
     * @Route("/", name="app_appointment_index", methods={"GET"})
     */
    public function index(AppointmentRepository $appointmentRepository, UserRepository $userR): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        $manger = $this->get('security.token_storage')->getToken()->getUser();
        $manger->getUsername();
        $users = $userR->findBy([]);

        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findBy(array('MangerID'=>$manger->getId()),array('id' => 'desc')),
            'user'=>$users,
        ]);
    }
    /**
     * @Route("/clientappointment", name="app_client_appointment", methods={"GET"})
     */
    public function clientindex(AppointmentRepository $appointmentRepository, ManagerRegistry $doctrine, UserRepository $userR): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        $manger = $this->get('security.token_storage')->getToken()->getUser();
        $manger->getUsername();
        $users = $userR->findBy([]);

        $order = $doctrine->getRepository(Order::class)->findBy([]);


        return $this->render('appointment/client.html.twig', [
            'appointments' => $appointmentRepository->findBy(array('MangerID'=>$manger->getId()),array('id' => 'desc')),
            'user'=>$users,
            'order'=> $order,
        ]);
    }

    /**
     * @Route("/new", name="app_appointment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AppointmentRepository $appointmentRepository,ManagerRegistry $doctrine,AssignGroupRepository $assignGroup, UserRepository $userR, MailerInterface $mailer): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
         $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $usw = $userR->findBy(array('Manager'=>$user));
        $i=0;
        foreach ($usw as $key => $value) {
            # code...
            $us[$i] = $doctrine->getRepository(Order::class)->findBy(
                ['email' => $value->getEmail()]
            );
            $i++;
            //print_r($us->getId());
        }
        
        
        //$assignGroup = $assignGroup->findBy([]);

        // foreach($assignGroup as $assign){
        //     foreach($assign->getUser() as $users){
        //         if($users->getId() == $user->getId()){
        //             $mangerId = $users->getId();
        //             $groupId = $assign->getId();
        //         }
        //     }
        // }
        // $AssignGroupM = $doctrine->getRepository(AssignGroup::class)->findBy(
        //     ['id' => $groupId]
        // );
        // $mangerId =[];
        // foreach($AssignGroupM as $AssignM){
        //     foreach($AssignM->getUser() as $users){
        //         if($users->getId() != $user->getId()){
        //             $mangerId[] = $users->getId();
        //         }
        //     }           
        // }

        // $us =[];
        // foreach ($us as $key => $manger) {

        //    // $us[] = $userR->find($manger);
        //    print_r($manger->getId());
            
        // }
       

        $choices = [];
        foreach ($us as $key => $manger) {
            foreach ($manger as $choice) {
            $choices[$choice->getId()] = $choice->getId();
            }
        }

        $appointment = new Appointment();
        $form = $this->createFormBuilder($appointment)
        ->add('mangerId', HiddenType::class,array(
                      'data' => $user->getId(),
                  ))
        ->add('ClientId', ChoiceType::class, [
        'choices' => $choices,
        
        ])
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
        ->add('MangerStart', HiddenType::class, [
                    'data' => 'available',
                ])
        ->add('ClientStartDate', HiddenType::class, [
                    'required'=>false,
                ])
        ->add('ClientStartTime', HiddenType::class, [
                    'required'=>false,
                ])
        ->add('ClientEndTime', HiddenType::class, [
                    'required'=>false,
                ])
        ->add('ClientStatus', HiddenType::class, [
                    'data' => 'pending',
                ])
        ->add('BookingStatus', HiddenType::class, [
                    'data' => 'available',
                ])
        
            
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ClientId = $form->get('ClientId')->getData(); 

            $getorder= $doctrine->getRepository(Order::class)->findById($ClientId);

            
            $appointmentRepository->add($appointment, true);

            foreach ($getorder as $key => $value) {
               
                $email = (new TemplatedEmail())
                ->from(new Address('contact@thefinanzi.in', 'Finanzi'))
                ->to(new Address($value->getEmail()))
                ->subject('Your Exclusive Appointment with Your Assigned Manager')
                ->htmlTemplate('emails/onboadcall.html.twig')
                ->context(['username' => $value->getName(), 'mangername' => $user->getName(),'services' => $value->getProducts()]);
                $mailer->send($email);
            }

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_appointment_show", methods={"GET"})
     */
    public function show(Appointment $appointment, UserRepository $userR): Response
    {
        $users = $userR->findBy([]);

        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
            'user'=>$users,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_appointment_edit", methods={"GET", "POST"})
     */
    public function edit($id, Request $request, ManagerRegistry $doctrine, Appointment $appointment, AppointmentRepository $appointmentRepository,  UserRepository $userR): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        $appointment  = $doctrine->getRepository(Appointment::class)->find($id); 
        
        $apname= $doctrine->getRepository(User::class)->find($appointment->getClientId());
        

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $us = $userR->findBy(array('Manager'=>$user));
        $choices = [];
        $i=0;
        foreach ($us as $choice) {
            if ($apname != $choice->getName() ){
                # code...
                $choices[$i]['id'] = $choice->getId();
                $choices[$i]['name'] = $choice->getName();
                $i++;
            }

        }
        if ($request->isMethod('POST')) {
            $ClientId = $request->request->get('ClientId');
            $startdate = $request->request->get('startdate');
            $enddate = $request->request->get('enddate');

            $starttime = $request->request->get('starttime');
            $endtime = $request->request->get('endtime');
            

            $endtime = \DateTime::createFromFormat('h:i A',date('h:i A', strtotime($endtime)));            
            $enddate= \DateTime::createFromFormat('d/m/Y',date("d/m/Y", strtotime($enddate)));

            $startdate= \DateTime::createFromFormat('d/m/Y',date("d/m/Y", strtotime($startdate)));
            $starttime= \DateTime::createFromFormat('h:i A',date("h:i A", strtotime($starttime)));
            //$endtime= \DateTime::createFromFormat('h:i:sa',date("h:i:sa", strtotime("+1 hour", strtotime($starttime))));
            
            $entityManager =$this->getDoctrine()->getManager();
            $appointment  = $doctrine->getRepository(Appointment::class)->find($id);
            //$userdat = $doctrine->getRepository(User::class)->find($appointment->getMangerId());
            //$clientuserdat = $doctrine->getRepository(User::class)->find($appointment->getClientId());
            
            $appointment->setClientId($ClientId);

            $appointment->setStartDate($startdate);
            $appointment->setEndDate($enddate);
            $appointment->setStartTime($starttime);
            $appointment->setEndTime($endtime);
            
            
            $entityManager->persist($appointment);
            $entityManager->flush();

            flash()->addSuccess('Thank you! appointment Edit successfully');
        //return $this->redirectToRoute("app_dashboard");

        }

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentRepository->add($appointment, true);

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'choices' => $choices,
            'apname' => $apname,
        ]);
    }

    /**
     * @Route("/{id}", name="app_appointment_delete", methods={"POST"})
     */
    public function delete(Request $request, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $appointmentRepository->remove($appointment, true);
        }

        return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
