<?php

namespace App\Form;

use App\Entity\Affiliate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

class AffiliateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class,array(            
            'label' => false
        ))
        ->add('phoneno', TextType::class,array(
                    'label' => false
                ))
        ->add('email', TextType::class,array(
                    'label' => false
                ))
        ->add('address', TextType::class,array(
                    'label' => false
                ))
        ->add('panno', TextType::class,array(
                    'label' => false
                ))
        ->add('accountname', TextType::class,array(
                    'label' => false
                ))
        ->add('holder', TextType::class,array(
                    'label' => false
                ))
        ->add('accountno', TextType::class,array(
                    'label' => false
                ))
        ->add('IFSC', TextType::class,array(
                    'label' => false
                ))
                ->add('upiid', TextType::class,array(
                    'label' => false
                ))
        ->add('userid', HiddenType::class,array(
                    'label' => false
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affiliate::class,
        ]);
    }
}
