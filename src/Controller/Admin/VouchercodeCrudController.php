<?php

namespace App\Controller\Admin;

use App\Entity\Vouchercode;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;


class VouchercodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vouchercode::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $fields = [
            AssociationField::new('v'),
            TextField::new('code'),
            BooleanField::new('status'),
            
            
        ];

        return $fields;
    }
    
}
