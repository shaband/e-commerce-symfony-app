<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangeUserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'mapped' => false,
                'required' => true,
                'row_attr' => ['class' => 'u-s-m-b-30'],
                'label_attr' => ['class' => 'gl-label'],
                'attr' => ['class' => 'input-text input-text--primary-style w-100', 'placeholder' => 'Old Password'],
                'constraints'=>
                    new UserPassword([
                        'message' => 'Your current password is incorrect.',
                    ]),
            ]
            )
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Password',
                    'attr' => ['class' => 'input-text input-text--primary-style w-100'],
                    'row_attr' => ['class' => 'u-s-m-b-30'],
                    'label_attr' => ['class' => 'gl-label'],
                ],
                'second_options' => ['label' => 'Repeat Password',
                    'attr' => ['class' => 'input-text input-text--primary-style w-100'],
                    'row_attr' => ['class' => 'u-s-m-b-30'],
                    'label_attr' => ['class' => 'gl-label'],
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
                'required' => true,

            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Change Password',

                'attr' => [
                    'class' => 'btn btn--e-transparent-brand-b-2',
                ],
            ])
            ->add('reset', ResetType::class, [
                'attr' => [
                    'data-dismiss' => 'modal',
                    'class' => 'btn btn--e-grey-b-2',
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
