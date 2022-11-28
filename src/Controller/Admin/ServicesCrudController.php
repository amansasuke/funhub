<?php

namespace App\Controller\Admin;

use App\Entity\Services;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ServicesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Services::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        // return [
        //     TextField::new('servicesname'),
        //     AssociationField::new('category'),
        //       ];
        // $imageFile = TextareaField::new('thumbnailFile')->setFormType(VichImageType::class);
        // $image = TextareaField::new('thumbnail')->setBasePath('/images/thumbnails');

        $fields = [
            TextField::new('servicesname'),
            AssociationField::new('category'),
            TextareaField::new('thumbnailFile')->setFormType(VichImageType::class),
        ];

        // if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
        //     $fields[] = $image;
        // } else {
        //     $fields[] = $imageFile;
        // }
        return $fields;
    }
    
}
