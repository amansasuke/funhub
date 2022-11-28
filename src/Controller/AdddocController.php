<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Docofpro;

use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\DocumentsforproductRepository;
use App\Repository\OrderdocRepository;
use App\Entity\Orderdoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Controller\EntityType;
use App\Entity\Order;
use Symfony\Component\OptionsResolver\OptionsResolver;


class AdddocController extends AbstractController
{
    /**
     * @Route("/adddoc/{id}", name="app_adddoc")
     */
    public function index($id,Request $request, ManagerRegistry $doctrine, DocumentsforproductRepository $doc,SluggerInterface $slugger): Response
    {   

        $Orderdoc = new Orderdoc;
        $order = new Order;

        $form = $this->createFormBuilder($Orderdoc)
            ->add('docname', TextType::class,array(
                      'label' => ' ',
                  ))
            ->add('doclink', FileType::class,array(
                      'label' => ' ',
                  ))
            ->add('status', HiddenType::class,array(
                      'data' => 0,
                  ))
            ->add('remark', HiddenType::class,array(
                      'data' => 'submitdoc',
                  ))
            ->add('save', SubmitType::class, ['label' => 'Upload'])
            ->getForm();

        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //this will be test for new data from the add join for order doc
            // $order1 = $form->getData();
            //     $or = $doctrine->getRepository(Order::class)->find($id);
            //     $order1->getOrderid()->add($or);

            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($order1);
            // $entityManager->flush();
            // return $this->render('confirmation.html.twig')

            // //$or = $form->getData();
            $docname = $form->get('docname')->getData();                    
             $brochureFile = $form->get('doclink')->getData();

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
            }
                //$or = $this->getDoctrine()->getManager($order)->find($id);
                $or = $doctrine->getRepository(Order::class)->find($id);
                $Orderdoc->getOrderid()->add($or);
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                // docname
                //$Orderdoc->setDocname($docname);
                $entityManager = $this->getDoctrine()->getManager();
                $Orderdoc->setDocname($docname);
                $Orderdoc->setDoclink($newFilename);
                // foreach ($order as $key => $value) {
                //     print_r($value->getid());
                //     $Orderdoc->getOrderid()->add($value->getId());
                // }
                //$Orderdoc->addOrderid($order);
                $entityManager->persist($Orderdoc);
                $entityManager->flush();
                // $Orderdoc->setDoclink($newFilename);
                // $Orderdoc->setDoclink($newFilename);
            




            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($order);
            // $entityManager->flush();

            // $this->sendEmailConfirmation($order, $mailer);

            // $session->set('basket', []);

            return $this->render('dashboard/index.html.twig',[
                'order' => $order
            ]);
        }
        $doc = $doc->find($id);
        return $this->render('dashboard/adddoc.html.twig', [
            'form' => $form->createView(),
            'doc' => $doc

        ]);

        
        
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // return $this->render('dashboard/adddoc.html.twig', [
        //     'product' => 'pro',
            

            
        // ]);
    }

public function configureOptions(OptionsResolver $resolver)
        {
        $resolver->setDefaults([
        'data_class' => Order::class,
        ]);
        }
    
}
