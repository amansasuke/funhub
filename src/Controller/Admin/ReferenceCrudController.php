<?php

namespace App\Controller\Admin;

use App\Entity\Reference;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReferenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reference::class;
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
