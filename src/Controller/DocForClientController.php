<?php

namespace App\Controller;

use App\Entity\DocForClient;
use App\Form\DocForClientType;
use App\Repository\DocForClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

use App\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\Persistence\ManagerRegistry;

use Aws\S3\S3Client;

/**
 * @Route("/doc/for/client")
 */
class DocForClientController extends AbstractController
{
    /**
     * @Route("/", name="app_doc_for_client_index", methods={"GET"})
     */
    public function index(DocForClientRepository $docForClientRepository): Response
    {   
        $docForClient =  $docForClientRepository->findBy(array('Ordername'=>$_GET['orderid']));
        
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        return $this->render('doc_for_client/index.html.twig', [
            'doc_for_clients' => $docForClient,
        ]);
    }

    /**
     * @Route("/new", name="app_doc_for_client_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DocForClientRepository $docForClientRepository,SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        $docForClient = new DocForClient();

        $entityManager =$this->getDoctrine()->getManager();
        $Orderd = $doctrine->getRepository(Order::class)->find($_GET['orderid']);
       
        $form = $this->createFormBuilder($docForClient)
            
            ->add('Name', TextType::class,array(
                      'label' => ' ',
                  ))
            ->add('Doclink', FileType::class,array(
                      'label' => ' ',
                  ))
            ->add('Status', HiddenType::class,array(
                      'data' => 1,
                  ))
            
            
            ->getForm();
        //$form = $this->createForm(DocForClientType::class, $docForClient);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => 'ap-south-1',
                'credentials' => [
                    'key'    => 'AKIA6FS6JGND2EMRQZOH',
                    'secret' => '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'
                ]
            ]);
            //die;
            $docname = $form->get('Name')->getData();
            //$Ordername = $form->get('Ordername')->getData();
            $Status = $form->get('Status')->getData();                    
             $brochureFile = $form->get('Doclink')->getData();
             
             if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                // try {
                //     $brochureFile->move(
                //         $this->getParameter('brochures_directory'),
                //         $newFilename
                //     );
                // } catch (FileException $e) {
                //     // ... handle exception if something happens during file upload
                // }

                $bucket = 'newdocsfinanzi';
                $file_Path = $brochureFile->getPathname();
                $key = 'orderdocs/' . $_GET['userid'] . '/' . $newFilename;

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
            }
                //$or = $this->getDoctrine()->getManager($order)->find($id);
                // $or = $doctrine->getRepository(Order::class)->find($id);
                // $Orderdoc->getOrderid()->add($or);
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                // docname
                $entityManager = $this->getDoctrine()->getManager();
                $docForClient->setName($docname);
                $docForClient->setDoclink($result->get('ObjectURL'));
                $docForClient->setOrdername($Orderd);
                $docForClient->setStatus($Status);
                $entityManager->persist($docForClient);
                $entityManager->flush();
            

                // return $this->render('dashboard/index.html.twig',[
                //     'order' => $order
                // ]);
                flash()->addSuccess('Thank you! Documents Submitted  successfully');
                return $this->redirectToRoute("app_doc_for_client_new",array('orderid' => $_GET['orderid'], 'userid' => $_GET['userid']));
            // $docForClientRepository->add($docForClient, true);
            // return $this->redirectToRoute('app_doc_for_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('doc_for_client/new.html.twig', [
            'doc_for_client' => $docForClient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_doc_for_client_show", methods={"GET"})
     */
    public function show(DocForClient $docForClient): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        return $this->render('doc_for_client/show.html.twig', [
            'doc_for_client' => $docForClient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_doc_for_client_edit", methods={"GET", "POST"})
     */
    public function edit($id,Request $request, SluggerInterface $slugger, DocForClient $docForClient, DocForClientRepository $docForClientRepository, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        // $form = $this->createForm(DocForClientType::class, $docForClient);
        // $form->handleRequest($request);
        $entityManager =$this->getDoctrine()->getManager();
        $docForClient = $doctrine->getRepository(DocForClient::class)->find($id);
        // print_r($docForClient->getId());
        // die();
        $form = $this->createFormBuilder($docForClient)
            
            ->add('Name', TextType::class,array(
                      'label' => ' ',
                  ))
            ->add('Doclink', FileType::class,array(
                      'label' => ' ',
                      'data_class' => null,
                  ))
            ->add('Status', HiddenType::class,array(
                      'data' => 1,
                  ))
            
            
            ->getForm();
        //$form = $this->createForm(DocForClientType::class, $docForClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => 'ap-south-1',
                'credentials' => [
                    'key'    => 'AKIA6FS6JGND2EMRQZOH',
                    'secret' => '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'
                ]
            ]);
            $brochureFile = $form->get('Doclink')->getData();
            $brochureFile = $form->get('Doclink')->getData();
             
             if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                // try {
                //     $brochureFile->move(
                //         $this->getParameter('brochures_directory'),
                //         $newFilename
                //     );
                // } catch (FileException $e) {
                //     // ... handle exception if something happens during file upload
                // }

                $bucket = 'newdocsfinanzi';
                $file_Path = $brochureFile->getPathname();
                $key = 'orderdocs/' . $_GET['userid'] . '/' . $newFilename;

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

            }
            
            $docForClientRepository->add($docForClient, true);

            $entityManager = $this->getDoctrine()->getManager();
                
                $docForClient->setDoclink($result->get('ObjectURL'));
            
                $entityManager->persist($docForClient);
                $entityManager->flush();
            return $this->redirectToRoute('app_doc_for_client_index',array('orderid' => $_GET['orderid']), Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('doc_for_client/edit.html.twig', [
            'doc_for_client' => $docForClient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/{ord}", name="app_doc_for_client_delete", methods={"POST"})
     */
    public function delete($id,$ord,Request $request, DocForClient $docForClient, DocForClientRepository $docForClientRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MANGER', null, 'User tried to access a page without having ROLE_MANGER');
        if ($this->isCsrfTokenValid('delete'.$docForClient->getId(), $request->request->get('_token'))) {
            $docForClientRepository->remove($docForClient, true);
        }

        return $this->redirectToRoute('app_doc_for_client_index',array('orderid' => $ord), Response::HTTP_SEE_OTHER);
    }
}
