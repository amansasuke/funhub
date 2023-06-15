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
use App\Repository\DocforproRepository;

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
                if($useid->getId() == $userId ){
                //print_r($useid->getId());echo "==";  print_r($userId); echo"<br>";
                $assignorder[] = $doctrine->getRepository(Order::class)->find($value->getId());
                }
            } 
        }


        return $this->render('mangerdashbord/index.html.twig', [
            'assignorder' => $assignorder,
        ]);
    }


    /**
     * @Route("/submitdoc/{id}", name="app_submitdoc" )
     */
    public function submitdoc($id,ManagerRegistry $doctrine, OrderdocRepository $Orderdoc,DocforproRepository $docforpro): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_staff');
        $Orderdoc = $Orderdoc->findBy([]);

        
        $order = $doctrine->getRepository(Order::class)->findBy(
            ['id' => $id],  array('id' => 'desc')
        );
        
        foreach($order as $orderid){
            $pro = $orderid->getProducts()->getId();      
            echo "<br>";
        }
        $docforpro = $docforpro->findBy(['proinfo' => $pro],  array('id' => 'desc'));

        $rqedoc =[];
        $j=0;
        foreach($docforpro as $dof){
            foreach ($dof->getNewdocinfo() as $key => $value) {
                $rqedoc[$j]['name']=$value->getName();
                $j++;
            }
            
        }
        print_r($rqedoc);
       // die;

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

        return $this->render('manger/orderdoc.html.twig', [
            'sunmitdoc' => $sunmitdoc,
            'docforpro' => $rqedoc,
        ]);
    }

/**
     * @Route("/staffdoc/{id}")
     */
    public function staffdoc($id,ManagerRegistry $doctrine, OrderdocRepository $Orderdoc): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_staff');
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

        return $this->render('mangerdashbord/staffdoc.html.twig', [
            'sunmitdoc' => $sunmitdoc,
        ]);
    }

    /**
     * @Route("/editorder/{id}", name="app_editorder" )
     */
    public function editdoc($id,ManagerRegistry $doctrine, Request $request, OrderdocRepository $Orderdoc): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_staff');
        // $Orderdoc = $doctrine->getRepository(Orderdoc::class)->find($id);
        // $order = new Order;
        $status = $request->request->get('status');
        $remark = $request->request->get('remark');
        $orderid = $request->request->get('orderid');

            $entityManager =$this->getDoctrine()->getManager();
            $Orderd = $doctrine->getRepository(Orderdoc::class)->find($id);
            $Orderd->setStatus($status);
            $Orderd->setRemark($remark);
            $entityManager->persist($Orderd);
            $entityManager->flush();

            flash()->addSuccess('Thank you! Document Submit successfully');
            return $this->redirectToRoute('app_submitdoc', ['id' => $orderid]);
            //return $this->redirectToRoute("app_dashboard");
        // }

    }




    /**
     * @Route("/editorderstatus/{id}", name="app_editorderstatus"  )
     */
    public function editorderstatus($id,ManagerRegistry $doctrine, Request $request, OrderdocRepository $Orderdoc): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_AGENT', null, 'User tried to access a page without having ROLE_staff');
        
        //$order = new Order;        
        $order = $request->request->get('status');
        $userid = $request->request->get('userid');
        
        $o =filter_var($order, FILTER_VALIDATE_BOOLEAN);
       
        // $entityManager = $this->getDoctrine()->getManager();
        // $Orderdoc = $doctrine->getRepository(Order::class)->find($id);        
        // $Orderdoc->setDocstatus($o);
        // $entityManager->persist($order);
        // $entityManager->flush();

        $entityManager =$this->getDoctrine()->getManager();
        $Orderd = $doctrine->getRepository(Order::class)->find($id);
        $Orderd->setDocstatus($o);
        
        $entityManager->persist($Orderd);
        $entityManager->flush();

        flash()->addSuccess('Thank you! Order Doc status change successfully');
        return $this->redirectToRoute('app_userorder', ['id' => $userid]);
    
    }

    
    /**
     * @Route("/{id}/agentstatus", name="app_agentstatus", methods={"GET", "POST"})
     */
    public function agentstatus($id ,Request $request,ManagerRegistry $doctrine): Response
    {
        $agentstatus = $request->request->get('agentstatus');

        $entityManager =$this->getDoctrine()->getManager();
        $Orderd = $doctrine->getRepository(Order::class)->find($id);
        $Orderd->setAgentstatus($agentstatus);
        
        $entityManager->persist($Orderd);
        $entityManager->flush();
        flash()->addSuccess('Thank you! Status Update successfully');
        return $this->redirectToRoute("app_staffdashbord");
        //return new JsonResponse(array('statsu' => true, 'messages' => array('done')));
    }

}
