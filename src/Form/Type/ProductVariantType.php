<?php

namespace App\Form\Type;

use App\Entity\AttributeValue;
use App\Entity\ProductVariant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductVariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sku', TextType::class, ['required' => false])
            ->add('stock', IntegerType::class)
            ->add('price', BcNumberType::class)
            ->add('discount', BcNumberType::class, ['required' => false])
            ->add('isActive', CheckboxType::class, ['required' => false])
            ->add('attributeValues', EntityType::class, [
                'class' => AttributeValue::class,
                'choice_label' => fn (AttributeValue $value) => $value->getAttribute() . ': ' . $value,
                'multiple' => true,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductVariant::class]);
    }
}
