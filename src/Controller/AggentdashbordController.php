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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File;

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
                    $sunmitdoc[$i]['docremark'] = $Ordoc->getDocremark();

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
    public function editorderstatus($id,ManagerRegistry $doctrine, Request $request, OrderdocRepository $Orderdoc, MailerInterface $mailer): Response
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

        $email = (new TemplatedEmail())
            ->from(new Address('contact@thefinanzi.in', 'Finanzi'))
            ->to(new Address($Orderd->getEmail()))
            ->subject("Service Commencement - Let's Begin!")
            ->htmlTemplate('emails/orderstatus.html.twig')
            ->context(['username' => $Orderd->getName()]);
            $mailer->send($email);

        flash()->addSuccess('Thank you! Order Doc status change successfully');
        return $this->redirectToRoute('app_userorder', ['id' => $userid]);
    
    }

    /**
     * @Route("/norify/{id}", name="app_norify"  )
     */
    public function norify($id,ManagerRegistry $doctrine, Request $request, OrderdocRepository $Orderdoc, MailerInterface $mailer): Response
    {
        $userid = $request->request->get('userid');
        $entityManager =$this->getDoctrine()->getManager();
        $Orderd = $doctrine->getRepository(Order::class)->find($id);        

        $email = (new TemplatedEmail())
            ->from(new Address('contact@thefinanzi.in', 'Finanzi'))
            ->to(new Address($Orderd->getEmail()))
            ->subject("Sealed with Success: Your Final Documents Have Arrived")
            ->htmlTemplate('emails/inform.html.twig')
            ->context(['username' => $Orderd->getName(),'pro'=> $Orderd->getProducts()->getName()]);
            $mailer->send($email);

        flash()->addSuccess('Thank you! Notification sent to user ');
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

    /**
     * @Route("/staffprofile", name="app_staffprofile")
     */
    public function userprofile(Request $request,SluggerInterface $slugger): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $icon  = $user->getImgicon();       
        //if(empty($user->getImgicon())){
            $form = $this->createFormBuilder($user)
            ->add('email', HiddenType::class, [
                'data' => $user->getEmail(),
            ])
            ->add('name', TextType ::class,array(
                'label' => ' Full Name',
            ))
            ->add('address')
            ->add('pan_no', TextType ::class,array(
                'label' => ' PAN Number',
            ))
            // ->add('GSTno', TextType::class,array(
            //           'label' => ' GST No (Optional)',
            //           'required' => false,
            //       ))
            ->add('phone_no',TextType::class, [
                'label' => 'Phone No',
            'constraints' => [
                new Length([
                    'min' => 10,
                    'minMessage' => 'Your Phone Number should be at least {{ limit }} Number',
                    // max length allowed by Symfony for security reasons
                    'max' => 15,
                ]),
            ]]
            )
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Male' => 'male',
                    'Female' => 'female',                  
                ],
            ])
            // ->add('user_category', ChoiceType::class, [
            //     'choices'  => [
            //         'choose your category' => NULL,
            //         'Individual' => 'Individual',
            //         'Proprietor (Business)' => 'Proprietor (Business)',
            //         'Partnership Firm' => 'Partnership Firm',
            //         'Private Limited Company' => 'Private Limited Company ',                  
            //         'Limited Liability Partnership (LLP)' => 'Limited Liability Partnership (LLP)',
            //         'Non-Profit Organisation' => 'Non-profit Organisation',
            //         'One Person Company' => 'One Person Company',
            //         'Start-Up' => 'Start-Up',                  
            //     ],
            //     'label' => 'User Category',
            // ])
            ->add('imgicon', FileType::class, array(
                'data_class' => null,
                'required' => false,
                'label' => false,
                ))
            
            ->add('save', SubmitType::class, ['label' => 'Update Profile'])
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
                    }else{
                       $entityManager = $this->getDoctrine()->getManager();                       
                       $user->setImgicon($icon);
                        $entityManager->persist($user);
                        $entityManager->flush(); 
                    }
                    flash()->addSuccess('Thank you! Profile updated successfully');
                   // $this->addFlash('success', 'Thank you! profile update successfully');
                }
        // }else{
        //     $form = $this->createFormBuilder($user)
        //     ->add('imgicon', TextType::class,array(
        //               'label' => ' ',
        //           ))
            
        //     ->add('save', SubmitType::class, ['label' => 'Upload'])
        //     ->getForm();
        //     $form->handleRequest($request);
        // }

        $UserCategory =  $user->getUserCategory();
        $phoneno =  $user->getPhoneNo();
        $panno =  $user->getPanNo();
        
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return $this->render('mangerdashbord/staffprofile.html.twig', [
            'user' => $user,
            'Category'=>$UserCategory,
            'PanNo'=>$panno,
            'phoneno'=>$phoneno,
            'form' => $form->createView(),
        ]);
    }

}
