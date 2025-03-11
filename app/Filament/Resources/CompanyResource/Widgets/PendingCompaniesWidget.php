<?php

namespace App\Filament\Resources\CompanyResource\Widgets;

use Filament\Widgets\ChartWidget;

class PendingCompaniesWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'bubble';
    }
}
