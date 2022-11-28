<?php

namespace App\Controller\Admin;

use App\Entity\Audiopodcast;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Translation\TranslatableMessage;
use Vich\UploaderBundle\Form\Type\VichFileType;

class AudiopodcastCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Audiopodcast::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextareaField::new('description'),
            TextField::new('category'),
            TextField::new('audiolink'),
        ];
    }
    
}
