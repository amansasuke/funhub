<?php

namespace App\Controller;

use App\Entity\Eventbooking;
use App\Form\EventbookingType;
use App\Repository\EventbookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/eventbooking")
 */
class EventbookingController extends AbstractController
{
    /**
     * @Route("/", name="app_eventbooking_index", methods={"GET"})
     */
    public function index(EventbookingRepository $eventbookingRepository): Response
    {
        return $this->render('eventbooking/index.html.twig', [
            'eventbookings' => $eventbookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_eventbooking_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EventbookingRepository $eventbookingRepository): Response
    {
        $eventbooking = new Eventbooking();
        $form = $this->createForm(EventbookingType::class, $eventbooking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventbookingRepository->add($eventbooking, true);

            return $this->redirectToRoute('app_eventbooking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('eventbooking/new.html.twig', [
            'eventbooking' => $eventbooking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_eventbooking_show", methods={"GET"})
     */
    public function show(Eventbooking $eventbooking): Response
    {
        return $this->render('eventbooking/show.html.twig', [
            'eventbooking' => $eventbooking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_eventbooking_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Eventbooking $eventbooking, EventbookingRepository $eventbookingRepository): Response
    {
        $form = $this->createForm(EventbookingType::class, $eventbooking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventbookingRepository->add($eventbooking, true);

            return $this->redirectToRoute('app_eventbooking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('eventbooking/edit.html.twig', [
            'eventbooking' => $eventbooking,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_eventbooking_delete", methods={"POST"})
     */
    public function delete(Request $request, Eventbooking $eventbooking, EventbookingRepository $eventbookingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventbooking->getId(), $request->request->get('_token'))) {
            $eventbookingRepository->remove($eventbooking, true);
        }

        return $this->redirectToRoute('app_eventbooking_index', [], Response::HTTP_SEE_OTHER);
    }
}
