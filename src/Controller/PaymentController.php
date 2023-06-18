<?php

namespace App\Controller;

use Razorpay\Api\Api;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment", name="app_payment")
     */
    public function pay(): Response
    {
        $keyId = 'rzp_test_7fBzWp1ySpgsgJ'; // Replace with your Razorpay key ID
        $keySecret = 'XrjzAqYtJSO8cfsrbYDy909k'; // Replace with your Razorpay key secret

        $razorpay = new Api($keyId, $keySecret);

        // Use the $razorpay instance to interact with Razorpay API

        // Example usage:
        $order = $razorpay->order->create([
            'amount' => 1000,  // amount in paise (e.g., ₹10 in rupees)
            'currency' => 'INR',
            'receipt' => 'order_123',
        ]);

        // Process the Razorpay response and render a template
        return $this->render('payment/index.html.twig', [
            'order' => $order,
        ]);
    }
}
