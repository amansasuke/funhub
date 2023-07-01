<?php

// src/MessageHandler/GeneratePdfAndSendEmailHandler.php

namespace App\MessageHandler;

use App\Message\GeneratePdfAndSendEmailMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Order;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Twig\Environment;

class GeneratePdfAndSendEmailHandler implements MessageHandlerInterface
{
    private $mailer;
    private $twig;
    private $doctrine;

    public function __construct(MailerInterface $mailer,ManagerRegistry $doctrine, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->doctrine = $doctrine;
        $this->twig = $twig;
    }

    public function __invoke(GeneratePdfAndSendEmailMessage $message)
    {
        // Logic to generate PDF and send email
        // You can use the $message object to access any data needed for generating the PDF or sending the email

            

        $orderid = $message->getOrderId();

        $order = $this->doctrine->getRepository(Order::class)->findBy(
            ['id' => $orderid]
        );
        foreach ($order as $key => $value) {

            $invoiceHtml =  $this->twig->render('invoice_template.html.twig', [
            'order' => $value,
            // add any additional data here
            ]);
            // 1. Generate PDF invoice
            $dompdf = new Dompdf();
            $dompdf->loadHtml($invoiceHtml);
            $dompdf->setPaper('A4', 'portrait');

            // 2. Load external CSS stylesheets, if any
            $dompdf->set_option('isRemoteEnabled', true);

            // 3. Render the PDF
            $dompdf->render();
            $pdf = $dompdf->output();

            // 4. Save the PDF invoice
            $invoiceFileName = 'invoice_' . $value->getId() . '.pdf';
            $invoicePath = __DIR__ . '/../../public/invoices/' . $invoiceFileName;
            file_put_contents($invoicePath, $pdf);
          

            $emailsend = (new TemplatedEmail())
                    ->from('contact@thefinanzi.in')
                    ->to(new Address($value->getEmail(), $value->getName()))
                    ->subject('Order confirmation')
                    ->htmlTemplate('emails/invoice.html.twig')
                    ->attachFromPath($invoicePath, $invoiceFileName)
                    ->context(['order' => $value]);
        }

        $this->mailer->send($emailsend);
        // Example:
        // $orderId = $message->getOrderId();
        // Generate the PDF
        // Send the email with the PDF attachment
    }
}
