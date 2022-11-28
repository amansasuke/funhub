<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Userid')
            ->add('Starttime')
            ->add('Endtime')
            ->add('Startslot')
            ->add('StartSlot')
            ->add('StartSlotM')
            ->add('EndSlotM')
            ->add('MangetStatu')
            ->add('Clintstarttime')
            ->add('ClintEndtime')
            ->add('ClintStartSlot')
            ->add('ClintEndSlot')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
