<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\GenderEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
//                'constraints' => []
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password'],
                'invalid_message' => 'The password fields must match.',
                'required' => false,
                'second_options' => ['label' => 'Confirm Password'],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('first_name', TextType::class,[])
            ->add('last_name', TextType::class)
            ->add('phone', TelType::class)
            ->add('avatar', FileType::class, [

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                'required' => false,

                'constraints' => [
                    new Assert\File(
                        maxSize: '1024k',
                        extensions: ['png', 'jpg', 'jpeg', 'gif'],
                    )
                ],
            ])
            ->add('gender', EnumType::class, [
                'class' => GenderEnum::class,
                'required' => false
            ])
            ->add('birthdate', BirthdayType::class, [
                'required'   => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
