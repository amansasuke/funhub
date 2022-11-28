<?php

namespace App\Form;

use App\Entity\Affiliate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffiliateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('phoneno')
            ->add('email')
            ->add('address')
            ->add('panno')
            ->add('accountname')
            ->add('holder')
            ->add('accountno')
            ->add('IFSC')
            ->add('userid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Affiliate::class,
        ]);
    }
}
