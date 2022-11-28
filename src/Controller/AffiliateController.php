<?php

namespace App\Controller;

use App\Entity\Affiliate;
use App\Form\AffiliateType;
use App\Repository\AffiliateRepository;
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

use App\Repository\UserRepository;
use App\Repository\AffiliateproductRepository;
use App\Entity\Affiliateproduct;

/**
 * @Route("/affiliate")
 */
class AffiliateController extends AbstractController
{
    /**
     * @Route("/", name="app_affiliate_index", methods={"GET"})
     */
    public function index(AffiliateRepository $affiliateRepository,AffiliateproductRepository $ap, UserRepository $userR): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getId();

        $affiliates = $affiliateRepository->findBy(array('userid'=>$user->getId()));

        $affiliateuser = $userR->findBy(array('red_id'=>$user->getId()));
        $Affiliateproduct = $ap->findBy(array('affiliateuserid'=>$user->getId()));

        $month=[];
        $j=0;
        $totalsale=[];
        $array3=[];
        if(count($Affiliateproduct)!=0){
         foreach ($Affiliateproduct as $key => $value) {

            $m[] = date_format($value->getAdddate(),"n");
            $sale['price'][date_format($value->getAdddate(),"n")][$j]= $value->getAffiliateprice();
            $sale['month'][$j]= date_format($value->getAdddate(),"n");
                $j++;
        }
        $allmonth= array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0,12=>0);
        $monthorder = array_count_values($m);
         ksort($monthorder);
        

        $salemonth=[];
        foreach ($sale['price'] as $key => $value) {
            $salemonth[$key] = array_sum($value);
            
        }
        $totalsale=[];
        for ($i=1; $i <=count($allmonth) ; $i++) { 
             $totalsale[$i] = $allmonth[$i] + (empty($salemonth[$i]) ? 0 : $salemonth[$i]);
          }


        $array3=[];
        
        for ($i=1; $i <=count($allmonth) ; $i++) { 
             $array3[$i] = $allmonth[$i] + (empty($monthorder[$i]) ? 0 : $monthorder[$i]);
          }
}

        return $this->render('affiliate/index.html.twig', [
            'affiliates' => $affiliates,
            'affiliateuser'=> $affiliateuser,
            'Affiliateproduct'=> $Affiliateproduct,
            'monthorder'=>$array3,
            'totalsale'=>$totalsale,
        ]);
    }


    /**
     * @Route("/useraffiliate", name="app_user_affiliate_index", methods={"GET"})
     */
    public function useraffiliate(AffiliateRepository $affiliateRepository,AffiliateproductRepository $ap, UserRepository $userR): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getId();

        $affiliates = $affiliateRepository->findBy(array('userid'=>$user->getId()));

        $affiliateuser = $userR->findBy(array('red_id'=>$user->getId()));
        $Affiliateproduct = $ap->findBy(array('affiliateuserid'=>$user->getId()));

       

        return $this->render('affiliate/useraffilite.html.twig', [
            'affiliates' => $affiliates,
            'affiliateuser'=> $affiliateuser,
            'Affiliateproduct'=> $Affiliateproduct,
        ]);
    }


    /**
     * @Route("/orderaffiliate", name="app_order_affiliate_index", methods={"GET"})
     */
    public function orderaffiliate(AffiliateRepository $affiliateRepository,AffiliateproductRepository $ap, UserRepository $userR): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getId();

        $affiliates = $affiliateRepository->findBy(array('userid'=>$user->getId()));

        $affiliateuser = $userR->findBy(array('red_id'=>$user->getId()));
        $Affiliateproduct = $ap->findBy(array('affiliateuserid'=>$user->getId()));

        return $this->render('affiliate/orderaffilite.html.twig', [
            'affiliates' => $affiliates,
            'affiliateuser'=> $affiliateuser,
            'Affiliateproduct'=> $Affiliateproduct,
        ]);
    }

    /**
     * @Route("/new", name="app_affiliate_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AffiliateRepository $affiliateRepository): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $user->getUsername();

        $affiliate = new Affiliate();
         $form = $this->createFormBuilder($affiliate)
        ->add('name', TextType::class,array(
                      'data' => $user->getName(),
                      'label' => false
                  ))
        ->add('phoneno', TextType::class,array(
                      'data' => $user->getPhoneNo(),
                      'label' => false
                  ))
        ->add('email', TextType::class,array(
                      'data' => $user->getEmail(),
                      'label' => false
                  ))
        ->add('address', TextType::class,array(
                      'data' => $user->getAddress(),
                      'label' => false
                  ))
        ->add('panno', TextType::class,array(
                      'data' => $user->getPanNo(),
                      'label' => false
                  ))
        ->add('accountname', TextType::class,array(
                      'label' => false
                  ))
        ->add('holder', TextType::class,array(
                      'label' => false
                  ))
        ->add('accountno', TextType::class,array(
                      'label' => false
                  ))
        ->add('IFSC', TextType::class,array(
                      'label' => false
                  ))
        ->add('userid', HiddenType::class,array(
                      'data' => $user->getId(),
                      'label' => false
                  ))
        
            
            ->getForm();

        //$form = $this->createForm(AffiliateType::class, $affiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affiliateRepository->add($affiliate, true);

            return $this->redirectToRoute('app_affiliate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('affiliate/new.html.twig', [
            'affiliate' => $affiliate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_affiliate_show", methods={"GET"})
     */
    public function show(Affiliate $affiliate): Response
    {
        return $this->render('affiliate/show.html.twig', [
            'affiliate' => $affiliate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_affiliate_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Affiliate $affiliate, AffiliateRepository $affiliateRepository): Response
    {
        $form = $this->createForm(AffiliateType::class, $affiliate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $affiliateRepository->add($affiliate, true);

            return $this->redirectToRoute('app_affiliate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('affiliate/edit.html.twig', [
            'affiliate' => $affiliate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_affiliate_delete", methods={"POST"})
     */
    public function delete(Request $request, Affiliate $affiliate, AffiliateRepository $affiliateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affiliate->getId(), $request->request->get('_token'))) {
            $affiliateRepository->remove($affiliate, true);
        }

        return $this->redirectToRoute('app_affiliate_index', [], Response::HTTP_SEE_OTHER);
    }
}
