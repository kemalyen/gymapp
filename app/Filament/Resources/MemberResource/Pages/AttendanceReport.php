<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Attendance;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;

class AttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.resources.member-resource.pages.attendance-report';

    public $user;
    public function mount($record)
    {
        $this->user = User::find($record);
         
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('user_id', $this->user->id))
            ->columns([
                TextColumn::make('attendance_date')->label('Date')
            ])
            ->filters([
                
                Filter::make('created_at')
                ->form([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })   
            ])
            
            ;
    }
}
