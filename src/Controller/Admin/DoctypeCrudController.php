<?php

namespace App\Controller\Admin;

use App\Entity\Doctype;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;

use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class DoctypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Doctype::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('name')->renderAsHtml(),
            //TextEditorField::new('description'),
        ];
    }
    
}
