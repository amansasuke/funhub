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
        return $this->render('doc_for_client/index.html.twig', [
            'doc_for_clients' => $docForClientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_doc_for_client_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DocForClientRepository $docForClientRepository,SluggerInterface $slugger, ManagerRegistry $doctrine): Response
    {
        $docForClient = new DocForClient();
        $form = $this->createFormBuilder($docForClient)
            ->add('Ordername', EntityType::class,array(
                      'class' => Order::class,
                      'choice_label' => 'Name',
                  ))
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
            $docname = $form->get('Name')->getData();
            $Ordername = $form->get('Ordername')->getData();
            $Status = $form->get('Status')->getData();                    
             $brochureFile = $form->get('Doclink')->getData();

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
                // $or = $doctrine->getRepository(Order::class)->find($id);
                // $Orderdoc->getOrderid()->add($or);
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                // docname
                $entityManager = $this->getDoctrine()->getManager();
                $docForClient->setName($docname);
                $docForClient->setDoclink($newFilename);
                $docForClient->setOrdername($Ordername);
                $docForClient->setStatus($Status);
                $entityManager->persist($docForClient);
                $entityManager->flush();
            

                return $this->render('dashboard/index.html.twig',[
                    'order' => $order
                ]);
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
        return $this->render('doc_for_client/show.html.twig', [
            'doc_for_client' => $docForClient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_doc_for_client_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DocForClient $docForClient, DocForClientRepository $docForClientRepository): Response
    {
        $form = $this->createForm(DocForClientType::class, $docForClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $docForClientRepository->add($docForClient, true);

            return $this->redirectToRoute('app_doc_for_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('doc_for_client/edit.html.twig', [
            'doc_for_client' => $docForClient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_doc_for_client_delete", methods={"POST"})
     */
    public function delete(Request $request, DocForClient $docForClient, DocForClientRepository $docForClientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$docForClient->getId(), $request->request->get('_token'))) {
            $docForClientRepository->remove($docForClient, true);
        }

        return $this->redirectToRoute('app_doc_for_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
