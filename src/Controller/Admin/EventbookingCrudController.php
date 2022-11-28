<?php

namespace App\Controller\Admin;

use App\Entity\Eventbooking;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class EventbookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Eventbooking::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('manger'),
            DateField::new('Bookingstart'),
            TimeField::new('Bookingtime'),
            TextField::new('Duration'),
            NumberField::new('Userid'),
            TextField::new('Usermail'),
            TextField::new('Status'),
            
            
        ];
    }
    
}
