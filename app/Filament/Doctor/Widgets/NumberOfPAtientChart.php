<?php

namespace App\Filament\Doctor\Widgets;

use App\Models\Patient;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class NumberOfPAtientChart extends ChartWidget
{
    protected static ?string $heading = 'Number of Patients';

    protected function getData(): array
    {
        $data = Trend::model(Patient::class)
        ->between(
            start: now()->subYear(),
            end: now(),
        )
        ->perMonth()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Patients',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
