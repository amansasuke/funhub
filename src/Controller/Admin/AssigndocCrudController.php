<?php

namespace App\Controller\Admin;

use App\Entity\Assigndoc;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

class AssigndocCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Assigndoc::class;
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
