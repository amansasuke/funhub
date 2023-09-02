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
use Aws\S3\S3Client;


class AdddocController extends AbstractController
{
    /**
     * @Route("/adddoc/{id}", name="app_adddoc")
     */
    public function index($id,Request $request, OrderdocRepository $OrderdocRepository, ManagerRegistry $doctrine, DocumentsforproductRepository $doc,SluggerInterface $slugger): Response
    {   
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userid = $user->getId();

        $orderid=  $_GET['ordid'];
        $Orderdoc = new Orderdoc;
        $order = new Order;

        $form = $this->createFormBuilder($Orderdoc)
            ->add('docname', HiddenType::class,array(
                      'label' => ' ',
                  ))
            ->add('doclink', FileType::class,array(
                      'label' => ' ',
                      'required' => false,
                  ))
            ->add('status', HiddenType::class,array(
                      'data' => '',
                  ))
            ->add('remark', HiddenType::class,array(
                      'data' => 'Pending for Approval',
                  ))
            ->add('docremark', TextType::class,array(
                    'label' => false,
                    'required' => false,
                ))
            ->add('save', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             // Instantiate an Amazon S3 client.
             $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => 'ap-south-1',
                'credentials' => [
                    'key'    => 'AKIA6FS6JGND2EMRQZOH',
                    'secret' => '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'
                ]
            ]);
            
            $docname = $form->get('docname')->getData();
            $docremark = $form->get('docremark')->getData();
            $newFilename='';                    
             $brochureFile = $form->get('doclink')->getData();
        

            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                

                $bucket = 'newdocsfinanzi';
                $file_Path = $brochureFile->getPathname();
                $key = 'orderdocs/' . $userid . '/' . $newFilename;

                $maxFileSize = 2 * 1024 * 1024; // 2MB in bytes
                if (filesize($file_Path) > $maxFileSize) {                    
                    flash()->addError ('Sorry ! File size  is more than 2MB');
                    return $this->redirectToRoute("app_adddoc",array('id' => $id,'docname' => $docname,'ordid' => $orderid));
                } 
                try {
                    $result = $s3Client->putObject([
                        'Bucket' => $bucket,
                        'Key'    => $key,
                        'Body'   => fopen($file_Path, 'r'),                                
                    ]);

                    //echo "Image uploaded successfully. Image path is: " . $result->get('ObjectURL');
                } catch (Aws\S3\Exception\S3Exception $e) {
                    // echo "There was an error uploading the file.\n";
                    // echo $e->getMessage();
                }                
            
                //$or = $this->getDoctrine()->getManager($order)->find($id);
                $or = $doctrine->getRepository(Order::class)->find($id);
                $Orderdoc->getOrderid()->add($or);
                $status='0';
                $entityManager = $this->getDoctrine()->getManager();
                $Orderdoc->setDocname($docname);
                $Orderdoc->setDoclink($result->get('ObjectURL'));
                $Orderdoc->setDocremark($docremark);
                $Orderdoc->setStatus($status);
            }else{
                $or = $doctrine->getRepository(Order::class)->find($id);
                $Orderdoc->getOrderid()->add($or);
                $status='0';
                $entityManager = $this->getDoctrine()->getManager();
                $Orderdoc->setDocname($docname);
                $Orderdoc->setDoclink('');
                $Orderdoc->setDocremark($docremark);
                $Orderdoc->setStatus($status);
            }
                // foreach ($order as $key => $value) {
                //     print_r($value->getid());
                //     $Orderdoc->getOrderid()->add($value->getId());
                // }
                //$Orderdoc->addOrderid($order);
                $entityManager->persist($Orderdoc);
                $entityManager->flush();
                // $Orderdoc->setDoclink($newFilename);
                // $Orderdoc->setDoclink($newFilename);
            
                if (isset($_GET['docid'])) {
                    $Orderdoc = $OrderdocRepository->findById($_GET['docid']);
                    foreach ($Orderdoc as $key => $value) {
                        $OrderdocRepository->remove($value, true);
                    }              
                }



            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($order);
            // $entityManager->flush();

            // $this->sendEmailConfirmation($order, $mailer);

            // $session->set('basket', []);

            //$this->addFlash('success', 'Thank you! Document Submit successfully');
            flash()->addSuccess('Thank you! Document Submitted successfully');
            return $this->redirectToRoute("app_dashboard",array('ordid' => $orderid));
        }
        //$doc = $doc->find($id);
        return $this->render('dashboard/adddoc.html.twig', [
            'form' => $form->createView(),
            'doc' => $doc

        ]);

        
        
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // return $this->render('dashboard/adddoc.html.twig', [
        //     'product' => 'pro',
            

            
        // ]);
    }

    /**
     * @Route("/adddocdel/{id}", name="app_subdoc_delete", methods={"POST"})
     */
    public function delete(Request $request, Orderdoc $Orderdoc, OrderdocRepository $OrderdocRepository): Response
    {
       
        //$this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        if ($this->isCsrfTokenValid('delete'.$Orderdoc->getId(), $request->request->get('_token'))) {
            $OrderdocRepository->remove($Orderdoc, true);
            
        }
        
        flash()->addSuccess('Thank you! document deleted successfully');
        return $this->redirectToRoute("app_dashboard",array('ordid' => $_GET['ordid']));
        //return $this->redirectToRoute('app_appointment_index', [], Response::HTTP_SEE_OTHER);
    }

public function configureOptions(OptionsResolver $resolver)
        {
        $resolver->setDefaults([
        'data_class' => Order::class,
        ]);
        }
    
}
