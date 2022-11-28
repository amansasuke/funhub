<?php

namespace App\Controller\Admin;

use App\Entity\Doctype;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DoctypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Doctype::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
