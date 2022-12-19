<?php

namespace App\Controller\Admin;

use App\Entity\User;
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




class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        $imageFile = Field::new('thumbnailFile')->setFormType(VichImageType::class);
        $image = ImageField::new('imgicon')->setBasePath('/assets/img/user');

        $fields = [
            IdField::new('id'),
            TextField::new('name'),
            EmailField::new('email'),
            TextField::new('password')->hideOnIndex(),
            AssociationField::new('role'),
            TextField::new('pan_no'),
            TextareaField::new('address'),
            TextField::new('GSTno')->hideOnIndex(),
            TextField::new('phone_no'),
            TextField::new('gender')->hideOnIndex(),
            TextField::new('user_category'),
            IntegerField::new('red_id')->hideOnIndex(),
            IntegerField::new('wellet')->hideOnIndex(),
        ];

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $image;
        } else {
            $fields[] = $imageFile;
        }
        return $fields;
    }
    
}
