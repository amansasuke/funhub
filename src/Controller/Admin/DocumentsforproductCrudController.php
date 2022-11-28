<?php

namespace App\Controller\Admin;

use App\Entity\Documentsforproduct;

use App\Entity\Product;
use App\Entity\Doctype;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;


class DocumentsforproductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Documentsforproduct::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('productinfo'),
            AssociationField::new('docinfo'),
            BooleanField::new('status'),
           

        ];
    }
    
}
