<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayAttendence extends BaseWidget
{
    protected static ?string $heading = 'Today\'s Latest 50 Attendeces';
    protected static ?int $sort = 3;


    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->whereDate('created_at', Carbon::today())->orderByDESC('id')->limit(100)->with('user'))
            ->columns([
                TextColumn::make('user.name'),
                TextColumn::make('created_at')->dateTime(),
            ])->paginated(false);
    }
}
