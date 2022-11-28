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


class adminController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
         //return parent::index();

        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Fintastic');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Category', 'fas fa-file-word', Category::class);
        yield MenuItem::linkToCrud('Sub Category', 'fas fa-cogs', Services::class);
        yield MenuItem::linkToCrud('Service', 'fa fa-shopping-basket', Product::class);
        yield MenuItem::linkToCrud('Post', 'fa fa-sticky-note', Post::class);
        yield MenuItem::linkToCrud('Doctype', 'fa fa-file-word', Doctype::class);
        yield MenuItem::linkToCrud('Documentsforproduct', 'fa fa-file-word', Documentsforproduct::class);
        yield MenuItem::linkToCrud('Order', 'fa fa-file-word', Order::class);
        yield MenuItem::linkToCrud('AssignGroup', 'fa fa-file-word', AssignGroup::class);
        yield MenuItem::linkToCrud('Voucher', 'fa fa-certificate', Voucher::class);
        yield MenuItem::linkToCrud('Vouchercode', 'fa fa-code', Vouchercode::class);
        yield MenuItem::linkToCrud('Reference Patner', 'fa fa-file-word', Reference::class);
        yield MenuItem::linkToCrud('Audiopodcast', 'fa fa-file-word', Audiopodcast::class);
        yield MenuItem::linkToCrud('Videoposcast', 'fa fa-file-word', Videoposcast::class);
        yield MenuItem::linkToCrud('Booking', 'fa fa-calendar', Eventbooking::class);

        
    }
}
