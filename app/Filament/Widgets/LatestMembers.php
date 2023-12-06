<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestMembers extends BaseWidget
{
    protected static ?string $heading = 'Latest 50 members';
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role(['member', 'trial'])->orderByDesc('id')->latest(10))
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('roles.name'),
                TextColumn::make('plan_name')->label('Membership Plan'),
                TextColumn::make('membership_ending'),
            ])->paginated(false)->searchable(false);
    }
}
