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
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

use App\Repository\UserRepository;




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
        //$someRepository = $this->entityManager->getRepository(User::class);

        $fields = [
            //IdField::new('id'),
            TextField::new('name'),
            EmailField::new('email'),
            TextField::new('password')->hideOnIndex(),
            AssociationField::new('role')->setFormTypeOption('choice_label', 'name'),
            AssociationField::new('Manager')->setFormTypeOption('choice_label', 'name')->setFormTypeOptions([
                    'query_builder' => function (UserRepository $er) {
                        return $er->createQueryBuilder('u')
                        ->andWhere('u.user_category = :searchTerm')
                        ->setParameter('searchTerm', 'Manager')
                            ->orderBy('u.name', 'ASC');
                    },
                ]),
            TextField::new('pan_no')->hideOnIndex(),
            TextareaField::new('address')->hideOnIndex(),
            TextField::new('GSTno')->hideOnIndex(),
            TextField::new('phone_no')->hideOnIndex(),
            //TextField::new('gender')->hideOnIndex(),
            ChoiceField::new('gender')->setChoices([
                    'Male'=>'male',
                    'Female'=>'female',
                     
            ]),
            //TextField::new('user_category'),
            ChoiceField::new('user_category')->setChoices([
                    'Manager'=>'manager',
                    'staff'=>'staff',
                    'Individual' => 'Individual',
                    'Proprietor (Business)' => 'Proprietor (Business)',
                    'Partnership Firm' => 'Partnership Firm',
                    'Private Limited Company' => 'Private Limited Company ',                  
                    'Limited Liability Partnership (LLP)' => 'Limited Liability Partnership (LLP)',
                    'Non-Profit Organisation' => 'Non-profit Organisation',
                    'One Person Company' => 'One Person Company',
                    'Start-Up' => 'Start-Up', 
            ]),            
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
