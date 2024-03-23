<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AttendenceChart extends ChartWidget
{
    protected static ?string $heading = 'Attendence Overview';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $attendences = Attendance::query()->where('created_at', '>', Carbon::now()->subDays(30))->latest()->get()->groupBy(function ($item) {
            return $item->created_at->format('d-M-y');
        });
    
        $stat = [];
        $date = [];
        $count = 0;
        foreach($attendences as $attendence){
            $count += $attendence->count();
            $stat[] = $attendence->count();
            $date[] = $attendence->first()->created_at->format('d-M-y');
        }
    
        return [
            'datasets' => [
                [
                    'label' => $count . ' attendeces in last 30 days',
                    'data' => $stat,
                ],
            ],
            'labels' => $date,
        ];

    }

    protected function getType(): string
    {
        return 'line';
    }
}
