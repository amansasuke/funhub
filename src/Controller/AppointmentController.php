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


use App\Repository\AssignGroupRepository;
use App\Repository\AssignGroupUserRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\AssignGroup;
use App\Entity\AssignGroupUser;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\OrderdocRepository;

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
            'appointments' => $appointmentRepository->findBy(array('MangerID'=>$manger->getId())),
            'user'=>$users,
        ]);
    }

    /**
     * @Route("/new", name="app_appointment_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AppointmentRepository $appointmentRepository,ManagerRegistry $doctrine,AssignGroupRepository $assignGroup, UserRepository $userR): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
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

        $choices = [];
        foreach ($us as $choice) {
        $choices[$choice->getName()] = $choice->getId();
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
            $appointmentRepository->add($appointment, true);

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
    public function show(Appointment $appointment): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_appointment_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Appointment $appointment, AppointmentRepository $appointmentRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointmentRepository->add($appointment, true);

            return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
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
