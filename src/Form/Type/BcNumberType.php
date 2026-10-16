<?php

namespace App\Form\Type;

use BcMath\Number;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Bridges Doctrine's Types::NUMBER (BcMath\Number) with a plain text input.
 */
class BcNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new CallbackTransformer(
            fn (?Number $number) => $number === null ? '' : (string) $number,
            fn (?string $value) => $value === null || $value === '' ? null : new Number($value),
        ));
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
