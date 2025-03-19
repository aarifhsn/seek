<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum JobStatus: string implements HasLabel
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case EXPIRED = 'pending';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::EXPIRED => 'Expired',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
            self::EXPIRED => 'warning',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn(JobStatus $enum) => [$enum->value => $enum->getLabel()]
        )->toArray();
    }
}