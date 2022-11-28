<?php

namespace App\Controller\Admin;

use App\Entity\AssignGroupUser;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Translation\TranslatableMessage;

class AssignGroupUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AssignGroupUser::class;
    }

    
    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         AssociationField::new('user'),
    //         AssociationField::new('name'),
        
    //     ];
    // }
    
}
