<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum GenderEnum: int implements TranslatableInterface
{
case Male=0;
case Female=1;

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        // Translate enum from name (Left, Center or Right)
        return $translator->trans($this->name, locale: $locale);

        // Translate enum using custom labels
//        return match ($this) {
//            self::Male  => $translator->trans('text_align.left.label', locale: $locale),
//            self::Female => $translator->trans('text_align.center.label', locale: $locale),
//            self::Right  => $translator->trans('text_align.right.label', locale: $locale),
//        };
    }
}
