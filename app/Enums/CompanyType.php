<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CompanyType: string implements HasLabel
{
    case FREE = 'free';
    case PREMIUM = 'premium';

    public function getLabel(): string
    {
        return match ($this) {
            self::FREE => 'Free',
            self::PREMIUM => 'Premium',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::FREE => 'heroicon-s-check-badge',
            self::PREMIUM => 'heroicon-s-check-circle',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn(CompanyType $enum) => [$enum->value => $enum->getLabel()]
        )->toArray();
    }
}