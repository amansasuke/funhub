<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (isset($_GET['affiliateid'])) {
            $redid = $_GET['affiliateid'];
        }else{
            $redid = 0;
        }
        $builder
            ->add('email', TextType ::class,array(
                'label' => false,
            ))
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => ' I AGREE WITH TERMS AND CONDITIONS ',
                'attr' => [
                'class' => 'float-right',
            ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => false],
                'second_options' => ['label' => false],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('name', TextType ::class,array(
                'label' => false,
            ))
            ->add('address', TextType ::class,array(
                'label' => false,
            ))
            ->add('pan_no', TextType ::class,array(
                'label' => ' PAN Number',
                'label' => false,
            ))
            ->add('GSTno', TextType::class,array(
                      'label' => false,
                      'required' => false,
                  ))
            ->add('phone_no',NumberType::class, [
                'label' => false,
            'constraints' => [
                new Length([
                    'min' => 10,
                    'minMessage' => 'Your Phone Number should be at least {{ limit }} Number',
                    // max length allowed by Symfony for security reasons
                    'max' => 15,
                ]),

                
            ]]
            )
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                     NULL => NULL,
                    'Male' => 'male',
                    'Female' => 'female',                  
                ],
            ])
            ->add('user_category', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                    NULL => NULL,
                    'Individual' => 'Individual',
                    'Proprietor (Business)' => 'Proprietor (Business)',
                    'Partnership Firm' => 'Partnership Firm',
                    'Private Limited Company' => 'Private Limited Company ',                  
                    'Limited Liability Partnership (LLP)' => 'Limited Liability Partnership (LLP)',
                    'Non-Profit Organisation' => 'Non-profit Organisation',
                    'One Person Company' => 'One Person Company',
                    'Start-Up' => 'Start-Up',

                ],                
            ])
            //->add('red_id')
            ->add('red_id', HiddenType::class,array(
                'data' => $redid,
                'label' => false
            ))
            ->add('wellet')
            ->add('imgicon', FileType::class,array(
                'label' => ' ',
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
