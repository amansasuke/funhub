<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\BookingRepository;
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

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\OrderdocRepository;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="app_booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_booking_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BookingRepository $bookingRepository,
    ManagerRegistry $doctrine,AssignGroupRepository $assignGroup, AssignGroupUserRepository $assignUser, UserRepository $userR): Response
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

        $choices = [];
        foreach ($us as $choice) {
            print_r($choice->getId());
        $choices[$choice->getId()] = $choice->getId();
        }




        $booking = new Booking();
        $form = $this->createFormBuilder($booking)
        //->add('userid', TextType::class)
        ->add('userid', ChoiceType::class, [
        'choices' => $choices,
        
        ])
        ->add('Starttime', DateType::class)
        ->add('Endtime', DateType::class)
        ->add('StartslotM', TimeType::class, [
                    'input'  => 'datetime',
                    'widget' => 'choice',
                ])
        ->add('EndSlotM', TimeType::class, [
                    'input'  => 'datetime',
                    'widget' => 'choice',
                ])
        
            
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking, true);

            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_booking_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking, true);

            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_booking_delete", methods={"POST"})
     */
    public function delete(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $bookingRepository->remove($booking, true);
        }

        return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
    }
}
