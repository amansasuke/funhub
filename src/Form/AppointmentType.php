<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('MangerID')
            ->add('ClientId')
            ->add('StartDate')
            ->add('EndDate')
            ->add('StartTime')
            ->add('EndTime')
            ->add('MangerStart')
            ->add('ClientStartDate')
            ->add('ClientStartTime')
            ->add('ClientEndTime')
            ->add('ClientStatus')
            ->add('BookingStatus')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
