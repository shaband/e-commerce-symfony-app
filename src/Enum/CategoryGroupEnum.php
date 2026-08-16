<?php

namespace App\Enum;

enum CategoryGroupEnum:string
{
    case Main="Main";
    case Group="Group";
    case Item = "Item";

    public function getParentCategory(): ?CategoryGroupEnum
    {
        return match ($this) {
            self::Main => null,
            self::Group => self::Main,
            self::Item => self::Group,
        };
    }
}
