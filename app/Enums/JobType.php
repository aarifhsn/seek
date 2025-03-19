<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum JobType: string implements HasLabel
{
    case FULL_TIME = 'full-time';
    case PART_TIME = 'part-time';
    case CONTRACT = 'contract';
    case TEMPORARY = 'temporary';
    case INTERNSHIP = 'internship';
    case VOLUNTEER = 'volunteer';
    case freelance = 'freelance';

    public function getLabel(): string
    {
        return match ($this) {
            JobType::FULL_TIME => 'Full Time',
            JobType::PART_TIME => 'Part Time',
            JobType::CONTRACT => 'Contract',
            JobType::TEMPORARY => 'Temporary',
            JobType::INTERNSHIP => 'Internship',
            JobType::VOLUNTEER => 'Volunteer',
            JobType::freelance => 'Freelance',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn(JobType $enum) => [$enum->value => $enum->getLabel()]
        )->toArray();
    }
}