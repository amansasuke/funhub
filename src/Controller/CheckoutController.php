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

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout")
     */
    public function checkout(Request $request, ProductRepository $repo, SessionInterface $session, MailerInterface $mailer, UserRepository $userR, AffiliateproductRepository $Affiliateproduct, ManagerRegistry $doctrine): Response
    {
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
            ->add('docstatus', HiddenType::class, [
                    'data' => '0',
                ])
            ->add('save', SubmitType::class, ['label' => 'Confirm order'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
             $Affiliateproducts = new Affiliateproduct;

            foreach ($basket as $product) {
                $order->getProducts()->add($repo->find($product->getId()));
            }
            $entityManager = $this->getDoctrine()->getManager();

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
                    $entityManager->persist($Affiliateproducts);

                }   
            }
            //end if user is affiliated

            
            $userdat->setWellet($waltebalanceNEW);
            $entityManager->persist($userdat);
            $entityManager->persist($order);
            $entityManager->flush();
        

            $this->sendEmailConfirmation($order, $mailer);

            $session->set('basket', []);

            return $this->render('confirmation.html.twig');
        }

        return $this->render('home/checkout.html.twig', [
            'total' => $total,
            'pro'=>$pro,
            'waltebalance'=>$waltebalance,
            'waltebalanceold'=>$waltebalanceold,
            'form' => $form->createView()
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
