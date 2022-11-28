<?php

namespace App\Controller\Admin;

use App\Entity\Docofpro;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Product;
use App\Entity\Doctype;

class DocofproCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Docofpro::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
          
        ];
    }
    
}
