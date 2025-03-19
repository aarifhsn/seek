<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
            self::PENDING => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-s-check-circle',
            self::INACTIVE => 'heroicon-s-x-circle',
            self::PENDING => 'heroicon-s-clock',
            self::APPROVED => 'heroicon-s-check-circle',
            self::REJECTED => 'heroicon-s-x-circle',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn(StatusEnum $enum) => [$enum->value => $enum->getLabel()]
        )->toArray();
    }
}