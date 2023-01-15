<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\OrderdocRepository;
use App\Entity\Orderdoc;
use App\Entity\User;
use App\Repository\UserRepository;

class AggentdashbordController extends AbstractController
{
    /**
     * @Route("/staffdashboard", name="app_staffdashbord")
     * 
     */
    public function index(ManagerRegistry $doctrine, UserRepository $userR): Response
    {   
        $this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_AGENT ');
        $assignorder = [];
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $order = $doctrine->getRepository(Order::class)->findBy([]);

        foreach ($order as $key => $value) {
            $userod= $value->getUser();
            foreach ($userod as $key => $useid) {

                $assignorder[] = $doctrine->getRepository(Order::class)->find($value->getId());
            } 
        }


        return $this->render('mangerdashbord/index.html.twig', [
            'assignorder' => $assignorder,
        ]);
    }


    /**
     * @Route("/submitdoc/{id}")
     */
    public function submitdoc($id,ManagerRegistry $doctrine, OrderdocRepository $Orderdoc): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_staff');
        $Orderdoc = $Orderdoc->findBy([]);

        $sunmitdoc =[];
        $i = 0;
        foreach($Orderdoc as $Ordoc){
            foreach($Ordoc->getOrderid() as $Oroc){
                if($Oroc->getId() == $id){
                    $sunmitdoc[$i]['id'] = $Oroc->getId();
                    $sunmitdoc[$i]['docid'] = $Ordoc->getId();
                    $sunmitdoc[$i]['docname'] = $Ordoc->getDocname();
                    $sunmitdoc[$i]['doclink'] = $Ordoc->getDoclink();
                    $sunmitdoc[$i]['docstatus'] = $Ordoc->getStatus();
                    $sunmitdoc[$i]['remark'] = $Ordoc->getRemark();

                    $i++;
                }
            }
        }

        return $this->render('mangerdashbord/Orderdoc.html.twig', [
            'sunmitdoc' => $sunmitdoc,
        ]);
    }

    /**
     * @Route("/editorder/{id}")
     */
    public function editdoc($id,ManagerRegistry $doctrine, Request $request, OrderdocRepository $Orderdoc): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_staff');
        $Orderdoc = $doctrine->getRepository(Orderdoc::class)->find($id);
        $order = new Order;

        $form = $this->createFormBuilder($Orderdoc)
            
            ->add('status', ChoiceType::class,[
                      'choices'  => [
                    'Submited Doc' => '0',
                    'Done' => '1',
                    'Submit Again' => '2',                  
                ],
                  ])
            ->add('remark', TextType::class,array(
                      'data' => ' ',
                  ))
            // ->add('remark', ChoiceType::class, [
            //     'choices'  => [
            //         'Submited Doc' => 'Submited Doc',
            //         'Done' => 'Done',
            //         'Submit Again' => 'Submit Again',                  
            //     ],
            // ])
            ->add('save', SubmitType::class, ['label' => 'Edit order documents Statusasas'])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->render('confirmation.html.twig');
        }

        return $this->render('mangerdashbord/editdoc.html.twig', [
          'form' =>$form->createView(),
        ]);
    }




    /**
     * @Route("/editorderstatus/{id}")
     */
    public function editorderstatus($id,ManagerRegistry $doctrine, Request $request, OrderdocRepository $Orderdoc): Response
    {
        $this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_staff');
        $Orderdoc = $doctrine->getRepository(Order::class)->find($id);
        //$order = new Order;

        $form = $this->createFormBuilder($Orderdoc)
            
            ->add('docstatus', TextType::class,array(
                      'data' => ' ',
                  ))
        
            ->add('save', SubmitType::class, ['label' => 'Edit order documents Status'])
            ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            return $this->render('confirmation.html.twig');
        }

        return $this->render('mangerdashbord/editorderdoc.html.twig', [
          'form' =>$form->createView(),
        ]);
    }

}
