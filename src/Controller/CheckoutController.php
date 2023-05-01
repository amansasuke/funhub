<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AffiliateproductRepository;
use App\Entity\Affiliateproduct;
use App\Entity\Affiliate;

use Dompdf\Dompdf;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout")
     */
    public function checkout(Request $request, ProductRepository $repo, SessionInterface $session, MailerInterface $mailer, UserRepository $userR, AffiliateproductRepository $Affiliateproduct, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'User tried to access a page without having ROLE_USER');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $waltebalanceold = $user->getWellet();
        $userdat = $doctrine->getRepository(User::class)->find($user->getId());
       

        $basket = $session->get('basket', []);
        $total = array_sum(array_map(function($product) { return $product->getPrice(); }, $basket));
        $pro = [];
        $i= 0;
        foreach ($basket as $product) {
                $pro[$i]['id'] = $product->getId();
                $pro[$i]['name'] = $product->getName();
                $pro[$i]['price'] = $product->getPrice();
                $i++;
            }

        $percentage =10;
        $new_width = ($percentage / 100) * $total;
        $waltebalance =  round($new_width);
        $waltebalanceNEW= $waltebalanceold + $waltebalance;

        $order = new Order;

        if($user->getGSTno() != null){
            $gst =$user->getGSTno();
        }else{
            $gst="";
        }

        $form = $this->createFormBuilder($order)
            ->add('name', TextType::class,[
                    'data' => $user->getName(),
                ])
            ->add('email', TextType::class, [
                    'data' => $user->getEmail(),
                ])
            ->add('address', TextareaType::class, [
                    'data' => $user->getAddress(),
                ])
            ->add('phoneno', TextType::class, [
                'data' => $user->getPhoneno(),
                'label' => 'phone no',
            ])

            ->add('state', ChoiceType::class, [
                'choices'  => [
                    'Andhra Pradesh' => 'Andhra Pradesh',
                    'Andaman and Nicobar Islands' => 'Andaman and Nicobar Islands',
                    'Arunachal Pradesh' => 'Arunachal Pradesh',
                    'Assam' => 'Assam',
                    'Bihar' => 'Bihar',
                    'Chandigarh' => 'Chandigarh',
                    'Chhattisgarh' => 'Chhattisgarh',
                    'Dadar and Nagar Haveli' => 'Dadar and Nagar Haveli',
                    'Daman and Diu' => 'Daman and Diu',
                    'Delhi' => 'Delhi',
                    'Lakshadweep' => 'Lakshadweep',
                    'Puducherry' => 'Puducherry',
                    'Goa' => 'Goa',                  
                    'Gujarat' => 'Gujarat',
                    'Haryana' => 'Haryana',
                    'Himachal Pradesh' => 'Himachal Pradesh',
                    'Jammu and Kashmir' => 'Jammu and Kashmir',
                    'Jharkhand' => 'Jharkhand',
                    'Karnataka' => 'Karnataka',
                    'Kerala' => 'Kerala',
                    'Madhya Pradesh' => 'Madhya Pradesh',
                    'Maharashtra' => 'Maharashtra',
                    'Manipur' => 'Manipur',
                    'Meghalaya' => 'Meghalaya',
                    'Mizoram' => 'Mizoram',
                    'Nagaland' => 'Nagaland',
                    'Odisha' => 'Odisha',
                    'Punjab' => 'Punjab',
                    'Rajasthan' => 'Rajasthan',
                    'Sikkim' => 'Sikkim',
                    'Tamil Nadu' => 'Tamil Nadu',
                    'Telangana' => 'Telangana',
                    'Tripura' => 'Tripura',
                    'Uttar Pradesh' => 'Uttar Pradesh',
                    'Uttarakhand' => 'Uttarakhand',
                    'West Bengal' => 'West Bengal',
                ],
            ])
            
            ->add('gstno', TextType::class, [
                'data' => $gst,
                'label' => 'GST No (Put your correct GST No. to claim Input Tax Credit)',
                'required' => false,
            ])
            ->add('docstatus', HiddenType::class, [
                    'data' => '0',
                ])
            // ->add('createat', DateType::class, [
            //     "data" => new \DateTime(),   
            //     ])
            ->add('grossvalue', HiddenType::class)
            ->add('gstamount', HiddenType::class)
            ->add('totalvalue', HiddenType::class)
            ->add('save', SubmitType::class, ['label' => 'Confirm order'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$order = $form->getData();
            $name= $form->get('name')->getData();
            $email= $form->get('email')->getData();
            $address= $form->get('address')->getData();
            $docstatus= $form->get('docstatus')->getData();
            $state= $form->get('state')->getData();
            $gstno= $form->get('gstno')->getData();
            $grossvalue= $form->get('grossvalue')->getData();
            $gstamount= $form->get('gstamount')->getData();
            $totalvalue= $form->get('totalvalue')->getData();
            // $createat= $form->get('createat');
            // print_r($createat);
            // die;
            // $createdate= \DateTime::createFromFormat('d/m/Y',date("d/m/Y", strtotime($createat)));
            $name= $form->get('name')->getData();
            $Affiliateproducts = new Affiliateproduct;

             $entityManager = $this->getDoctrine()->getManager();
            foreach ($basket as $product) {
                $order = new Order;
                $order->setName($name);
                $order->setEmail($email);
                $order->setAddress($address);
                $order->setDocstatus($docstatus);
                $order->setState($state);
                $order->setGstno($gstno);
                $order->setCreateat(new \DateTime('today'));
                $order->setGrossvalue($grossvalue);
                $order->setGstamount($gstamount);
                $order->setTotalvalue($totalvalue);
                $order->setProducts($repo->find($product->getId()));                
                $entityManager->persist($order);
                $entityManager->flush();  
                
                 // 2. Create a Twig template for the invoice
            $invoiceHtml = $this->renderView('invoice_template.html.twig', [
                'order' => $order,
                // add any additional data here
            ]);
            

            // 3. Generate invoice data
            $invoiceData = [
                'orderNumber' => 'name',
                'items' => 'itme',
                'totalCost' => '9999',
                // add any additional data here
            ];

            // 4. Generate PDF invoice
            $dompdf = new Dompdf();
            $dompdf->loadHtml($invoiceHtml);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $pdf = $dompdf->output();

            // 5. Save the PDF invoice
            $invoiceFileName = 'invoice_' . $order->getId() . '.pdf';
            $invoicePath = __DIR__ . '/../../public/invoices/' . $invoiceFileName;
            file_put_contents($invoicePath, $pdf);

            // 6. Send email with invoice
            $emailsend = (new TemplatedEmail())
            ->from('amansharmasasuke@gmail.com')
            ->to(new Address($order->getEmail(), $order->getName()))
            ->subject('Order confirmation')
            ->htmlTemplate('emails/invoice.html.twig')
            ->attachFromPath($invoicePath, $invoiceFileName)
            ->context(['order' => $order]);

            $mailer->send($emailsend);
            }         

             //add if user is affiliated
            if (!empty($user->getRedId())) {
                foreach ($basket as $product) {
                    $producname = $product->getName();
                    $producprice = $product->getPrice();
                    $getService = $product->getService()->getServicesname();
                    $orderuserid = $user->getId();
                    $affiliateuser = $user->getRedId();

                    $Affiliateoyo = $doctrine->getRepository(Affiliate::class)->findBy(array('userid'=>$affiliateuser));
                        foreach ($Affiliateoyo as $key => $value) {

                           $Affiliateoyodata = $value;
                        
                        }
                      if ($waltebalanceold == 0) {
                            $percentage =20;
                        }else{
                            $percentage =10;
                        }
                    $date = new \DateTime('@'.strtotime('now'));

                    $producpricePercentage = ($percentage / 100) * $producprice;
                    $producpricePercentage =  round($producpricePercentage);

                    $Affiliateproducts->setProductname($producname);
                    $Affiliateproducts->setServicename($getService);
                    $Affiliateproducts->setProductprice($producprice);
                    $Affiliateproducts->setAffiliateprice($producpricePercentage);
                    $Affiliateproducts->setAffiliateuserid($affiliateuser);
                    $Affiliateproducts->setOrderuserid($orderuserid);
                    $Affiliateproducts->setAffiliateid($Affiliateoyodata);
                    $Affiliateproducts->setAdddate($date);
                    $Affiliateproducts->setOrderinfo($order);
                    $entityManager->persist($Affiliateproducts);

                }   
            }
            //end if user is affiliated

            
            $userdat->setWellet($waltebalanceNEW);
            $entityManager->persist($userdat);
            //$entityManager->persist($order);
            $entityManager->flush();
        

            $this->sendEmailConfirmation($order, $mailer);

            $session->set('basket', []);
            //$this->addFlash('success', 'Thank you! Received your order successfully');
            //flash()->addSuccess('Thank you! Received your order successfully');
            //return $this->redirectToRoute("app_dashboard");
            
            flash()->addSuccess('Thank you! Received your order successfully');
            return $this->redirectToRoute("app_dashboard");
        }

        return $this->render('home/checkout.html.twig', [
            'total' => $total,
            'pro'=>$pro,
            'waltebalance'=>$waltebalance,
            'waltebalanceold'=>$waltebalanceold,
            'form' => $form->createView(),
            'basket'=>$basket,
            'phone' =>$user->getPhoneno(),
        ]);
    }

    private function sendEmailConfirmation(Order $order, MailerInterface $mailer)
    {
        $email = (new TemplatedEmail())
            ->from('amansharmasasuke@gmail.com')
            ->to(new Address($order->getEmail(), $order->getName()))
            ->subject('Order confirmation')
            ->htmlTemplate('emails/order.html.twig')
            ->context(['order' => $order]);

        $mailer->send($email);
    }
}
