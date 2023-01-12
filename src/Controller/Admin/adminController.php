<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Services;
use App\Entity\Post;
use App\Entity\Product;
use App\Entity\Doctype;
use App\Entity\Documentsforproduct;
use App\Entity\Order;
use App\Entity\Assigndoc;
use App\Entity\AssignGroup;
use App\Entity\AssignGroupUser;
use App\Entity\Vouchercode;
use App\Entity\Voucher;
use App\Entity\Reference;
use App\Entity\Audiopodcast;
use App\Entity\Videoposcast;
use App\Entity\Eventbooking;
use App\Entity\Docforpro;
use App\Entity\Affiliate;
use App\Entity\Affiliateproduct;

class adminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
         //return parent::index();

        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('The Finanzi');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class)->setDefaultSort(['id' => 'DESC']);
        
        yield MenuItem::section('Product');
        yield MenuItem::linkToCrud('Category', 'fas fa-file-word', Category::class);
        yield MenuItem::linkToCrud('Sub Category', 'fas fa-cogs', Services::class);
        yield MenuItem::linkToCrud('Service', 'fa fa-shopping-basket', Product::class);

        yield MenuItem::section('Documents for product');
        yield MenuItem::linkToCrud('Documents For Product', 'fa fa-file-word', Docforpro::class);
        
        yield MenuItem::section('product Doc Type');
        yield MenuItem::linkToCrud('Documents Type', 'fa fa-file-word', Doctype::class)->setDefaultSort(['id' => 'DESC']);

        yield MenuItem::section('order');
        yield MenuItem::linkToCrud('Order', 'fa fa-file-word', Order::class)->setDefaultSort(['id' => 'DESC']);

        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Post', 'fa fa-sticky-note', Post::class);

        yield MenuItem::section('Prodcast (club)');
        yield MenuItem::linkToCrud('Audiopodcast', 'fa fa-file-word', Audiopodcast::class);
        yield MenuItem::linkToCrud('Videoposcast', 'fa fa-file-word', Videoposcast::class);
        yield MenuItem::linkToCrud('Reference Patner', 'fa fa-file-word', Reference::class);

        yield MenuItem::section('Voucher');
        yield MenuItem::linkToCrud('Voucher', 'fa fa-certificate', Voucher::class);
        yield MenuItem::linkToCrud('Vouchercode', 'fa fa-code', Vouchercode::class);

        yield MenuItem::section('Affiliate;');
        yield MenuItem::linkToCrud('Affiliate', 'fa fa-file-word', Affiliate::class);
        yield MenuItem::linkToCrud('Affiliateproduct', 'fa fa-file-word', Affiliateproduct::class);
        
        

        yield MenuItem::section('Appointment');
        yield MenuItem::linkToCrud('Booking', 'fa fa-calendar', Eventbooking::class)->setDefaultSort(['id' => 'DESC']);


     
        
    }
}
