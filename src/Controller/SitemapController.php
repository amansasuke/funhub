<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap.xml", name="sitemap")
     */
    public function index(): Response
    {
        // You can fetch data from your database or generate routes here
        $urls = [
            ['loc' => $this->generateUrl('sitemap'), 'changefreq' => 'weekly', 'priority' => 0.8],
            // Add more URLs as needed
        ];

        $xml = $this->renderView('sitemap/index.xml.twig', [
            'urls' => $urls,
        ]);

        return new Response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
