<?php

namespace App\Controller\Admin;

use App\Entity\Documentsforproduct;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class Documentsforproduct2CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Documentsforproduct::class;
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
