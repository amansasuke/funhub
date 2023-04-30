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

use App\Service\CsvService;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;

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

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('id')
            ->add('name')
            ->add('email')
            // most of the times there is no need to define the
            // filter type because EasyAdmin can guess it automatically
            ->add(BooleanFilter::new('published'))
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $export = Action::new('export', 'export')
            ->setIcon('fa fa-download')
            ->linkToCrudAction('export')
            ->setCssClass('btn btn-success')
            ->createAsGlobalAction();

        return $actions->add(Crud::PAGE_INDEX, $export);
    }

    public function export(Request $request)
    {
        $context = $request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE);
        $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
        $filters = $this->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
        $users = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters)
            ->getQuery()
            ->getResult();

        $data = [];
        foreach ($users as $user) {
            $data[] = $user->getExportData();
        }

        return $this->exportcvs($data, 'export_users_'.date_create()->format('d-m-y').'.csv');
    }

    public function exportcvs($data, $filename)
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        $response = new Response($serializer->encode($data, CsvEncoder::FORMAT));
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");
        return $response;
    }

    public function import($filename, $options = [])
    {
        $serializer = new Serializer([new ObjectNormalizer()], [new CsvEncoder()]);
        return $serializer->decode(file_get_contents($filename), CsvEncoder::FORMAT, $options);
    }
    
}
