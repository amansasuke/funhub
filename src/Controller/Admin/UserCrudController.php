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




class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
        
            TextField::new('name'),
            EmailField::new('email'),
            TextField::new('password'),
            AssociationField::new('role'),
            TextField::new('pan_no'),
            TextareaField::new('address'),
            TextField::new('GSTno')->hideOnIndex(),
            TextField::new('phone_no'),
            TextField::new('gender'),
            TextField::new('user_category'),
            // ChoiceField::new('gender')->setChoices([
            //     'choose gender' => NULL,
            //     'Male' => 'male',
            //     'Female' => 'female',
            // ]),
            // ChoiceField::new('user_category')->setChoices([
            //     // $value => $badgeStyleName
            //     'choose category' => NULL,
            //         'Individual' => 'individual',
            //         'business owner' => 'business owner',
            //         'NPO' => 'NPO',
            //         'trader' => 'trader',
            // ]),
            IntegerField::new('red_id')->hideOnIndex(),
            IntegerField::new('wellet')->hideOnIndex(),
            TextField::new('imgicon')->hideOnIndex(),

            
            //TextEditorField::new('description'),
        ];
    }
    
}
