<?php

namespace App\Filament\Doctor\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Appointment;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class NumberOfAppointmentChart extends ChartWidget
{
    protected static ?string $heading = 'Number of Appointments';

    protected function getData(): array
    {
        $data = Trend::model(Appointment::class)
        ->between(
            start: now()->subYear(),
            end: now(),
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Appointments',
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
