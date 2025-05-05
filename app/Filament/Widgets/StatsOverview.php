<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\App;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected ?string $description = 'An overview of some analytics.';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->label('Total Users')
                ->description('Total number of users registered')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Total Doctors', Doctor::count())
                ->label('Total Doctors')
                ->description('Total number of doctors')
                ->icon('heroicon-o-user-circle')
                ->color('primary'),
            Stat::make('Total Patients', Patient::count())
                ->label('Total Patients')
                ->description('Total number of patients')
                ->icon('heroicon-o-user-group')
                ->color('secondary'),
            Stat::make('Total Appointments', Appointment::count())
                ->label('Total Appointments')
                ->description('Total number of appointments')
                ->icon('heroicon-o-calendar')
                ->color('warning'),
        ];
    }
}
