<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

use App\Service\CsvService;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;


class OrderCrudController extends AbstractCrudController
{
    // private CsvService $csvService;

    // public function __construct(CsvService $csvService)
    // {
    //     $this->csvService = $csvService;
    // }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('email'),
            AssociationField::new('products')->setFormTypeOption('choice_label', 'name'),
            //CollectionField::new('products')->setTemplatePath('admin/fields/orderpro.html.twig'),
            //AssociationField::new('user'),
            CollectionField::new('user')->setTemplatePath('admin/fields/orderstaff.html.twig'),
            // DateField::new('startdate'),
            // DateField::new('enddate'),
            TextField::new('agentstatus'),
            BooleanField::new('docstatus'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('name')
            ->add('createat')
            // most of the times there is no need to define the
            // filter type because EasyAdmin can guess it automatically
            ->add(BooleanFilter::new('published'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $export = Action::new('export', 'export')
            ->setIcon('fa fa-download')
            ->linkToCrudAction('export')
            ->setCssClass('btn btn-success')
            ->createAsGlobalAction();

        return $actions->add(Crud::PAGE_INDEX, $export);
    }

    public function export(Request $request)
    {
        $context = $request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE);
        $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
        $filters = $this->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
        $orders = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters)
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($orders as $order) {
            $data[] = $order->getExportData();
        }

        return $this->exportcvs($data, 'export_orders_'.date_create()->format('d-m-y').'.csv');
    }

    public function exportcvs($data, $filename)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $response = new Response($serializer->encode($data, CsvEncoder::FORMAT));
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");
        return $response;
    }

    public function import($filename, $options = [])
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        return $serializer->decode(file_get_contents($filename), CsvEncoder::FORMAT, $options);
    }
    
}
