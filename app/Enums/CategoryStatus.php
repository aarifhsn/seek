<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CategoryStatus: string implements HasLabel
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }


    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn(CategoryStatus $enum) => [$enum->value => $enum->getLabel()]
        )->toArray();
    }
}