<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Club;
use App\Repository\ClubRepository;
use App\Repository\ReferenceRepository;
use App\Repository\AudiopodcastRepository;
use App\Repository\VideoposcastRepository;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ClubController extends AbstractController
{
    /**
     * @Route("/club", name="app_club")
     */
    public function index(ClubRepository $club,ReferenceRepository $Reference, AudiopodcastRepository $podcast, VideoposcastRepository $videocast): Response
    {   
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $club = $club->findBy(array('user'=>$user));

        if(!empty($club)){
            $purches= 1;
        }else{
            $purches= 0;
        }
        $date = new \DateTime('@'.strtotime('now'));
        $expire= '';
        $paymentstatus= '';
        foreach ($club as $key => $value) {
            
            if ($value->getEndtime() < $date  ) {
               $expire= 0;
            }{
               $expire= 1;
            }
            if ($value->getPaymentstatus() =='Done' ) {
               $paymentstatus= 'Done';
            }{
               $paymentstatus= 'padding';
            }
        }



        $Reference = $Reference->findBy([]);
        $podcast = $podcast->findBy([]);
        $videocast = $videocast->findBy([]);

        return $this->render('club/index.html.twig', [
            'expire' => $expire,
            'paymentstatus' => $paymentstatus,
            'purches' => $purches,
            'Reference'=>$Reference,
            'podcast'=>$podcast,
            'videocast'=> $videocast,
        ]);
    }

    /**
     * @Route("/reference", name="app_reference")
     */
    public function reference(ClubRepository $club,ReferenceRepository $Reference, AudiopodcastRepository $podcast, VideoposcastRepository $videocast): Response
    {   
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $club = $club->findBy(array('user'=>$user));

        if(!empty($club)){
            $purches= 1;
        }else{
            $purches= 0;
        }
        $date = new \DateTime('@'.strtotime('now'));
        $expire= '';
        $paymentstatus= '';
        foreach ($club as $key => $value) {
            
            if ($value->getEndtime() < $date  ) {
               $expire= 0;
            }{
               $expire= 1;
            }
            if ($value->getPaymentstatus() =='Done' ) {
               $paymentstatus= 'Done';
            }{
               $paymentstatus= 'padding';
            }
        }



        $Reference = $Reference->findBy([]);
        $podcast = $podcast->findBy([]);
        $videocast = $videocast->findBy([]);

        return $this->render('club/reference.html.twig', [
            'expire' => $expire,
            'paymentstatus' => $paymentstatus,
            'purches' => $purches,
            'Reference'=>$Reference,
            'podcast'=>$podcast,
            'videocast'=> $videocast,
        ]);
    }

    /**
     * @Route("/audiopodcast", name="app_audiopodcast")
     */
    public function audiopodcast(ClubRepository $club,ReferenceRepository $Reference, AudiopodcastRepository $podcast, VideoposcastRepository $videocast): Response
    {   
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $club = $club->findBy(array('user'=>$user));

        if(!empty($club)){
            $purches= 1;
        }else{
            $purches= 0;
        }
        $date = new \DateTime('@'.strtotime('now'));
        $expire= '';
        $paymentstatus= '';
        foreach ($club as $key => $value) {
            
            if ($value->getEndtime() < $date  ) {
               $expire= 0;
            }{
               $expire= 1;
            }
            if ($value->getPaymentstatus() =='Done' ) {
               $paymentstatus= 'Done';
            }{
               $paymentstatus= 'padding';
            }
        }



        $Reference = $Reference->findBy([]);
        $podcast = $podcast->findBy([]);
        $videocast = $videocast->findBy([]);

        return $this->render('club/audiopodcast.html.twig', [
            'expire' => $expire,
            'paymentstatus' => $paymentstatus,
            'purches' => $purches,
            'Reference'=>$Reference,
            'podcast'=>$podcast,
            'videocast'=> $videocast,
        ]);
    }

    /**
     * @Route("/videopodcast", name="app_videopodcast")
     */
    public function videopodcast(ClubRepository $club,ReferenceRepository $Reference, AudiopodcastRepository $podcast, VideoposcastRepository $videocast): Response
    {   
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $club = $club->findBy(array('user'=>$user));

        if(!empty($club)){
            $purches= 1;
        }else{
            $purches= 0;
        }
        $date = new \DateTime('@'.strtotime('now'));
        $expire= '';
        $paymentstatus= '';
        foreach ($club as $key => $value) {
            
            if ($value->getEndtime() < $date  ) {
               $expire= 0;
            }{
               $expire= 1;
            }
            if ($value->getPaymentstatus() =='Done' ) {
               $paymentstatus= 'Done';
            }{
               $paymentstatus= 'padding';
            }
        }



        $Reference = $Reference->findBy([]);
        $podcast = $podcast->findBy([]);
        $videocast = $videocast->findBy([]);

        return $this->render('club/videopodcast.html.twig', [
            'expire' => $expire,
            'paymentstatus' => $paymentstatus,
            'purches' => $purches,
            'Reference'=>$Reference,
            'podcast'=>$podcast,
            'videocast'=> $videocast,
        ]);
    }

    /**
     * @Route("/join", name="app_join")
     */
    public function join(Request $request, ClubRepository $club,ReferenceRepository $Reference, AudiopodcastRepository $podcast, VideoposcastRepository $videocast): Response
    {   
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();
        $date = new \DateTime('@'.strtotime('now'));
        $enddate = new \DateTime('@'.strtotime('now'));
        $enddate= $enddate->modify('+1 month');
        $choices[$user->getId()] = $user->getId();
        $ClubC = new Club();
        $price =3000;
        if (!empty($_GET['usewalet'])) {
         
        if (!empty($request->isMethod('GET'))) {
            $price = $price - $_GET['usewalet']; 
        }else{
            $price =3000;
        }

        }
        $form = $this->createFormBuilder($ClubC)
        ->add('user', EntityType::class, [
                'class' => User::class,
                 'query_builder' => function (EntityRepository $er) {
                    $user = $this->get('security.token_storage')->getToken()->getUser();
                    return $er->createQueryBuilder('u')
                        ->where('u.id = :miarray')
                        ->setParameter('miarray',$user->getId());
                },
                'choice_label' => 'username',
                
            ])
        ->add('paymentstatus', HiddenType::class, [
                    'data' => 'Done',
                ])
        ->add('price', TextType::class, [
                    'data' => $price,
                    
                ])
        ->add('starttime', DateType::class, [
                    'data' => $date,
                    
                ])
        ->add('endtime', DateType::class, [
                    'data' => $enddate,
                    
                ])
        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $club->add($ClubC, true);

            return $this->redirectToRoute('app_club', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('club/joinclub.html.twig', [
            'form' => $form,
            'waltebalanceold'=>$user->getWellet(),
            'price'=>$price,
            'date' => $date,
            'enddate' => $enddate,

        ]);
    }


}
