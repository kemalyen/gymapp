<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Attendance;
use App\Models\User;
use Filament\Resources\Pages\Page;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Tables\Contracts\HasTable;

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
            ]);
    }


    public function memberInfo(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->user)
            ->schema([
               Components\Section::make('Member')

                    ->schema([
                        Components\Grid::make(2)
                            ->schema([

                                Components\Group::make([
                                    Components\TextEntry::make('name')->label('Name'),
                                    Components\TextEntry::make('name')->label('Email'),
                                    Components\TextEntry::make('phone')->label('Mobile Phone'),
                                ])->columns(3),

                                Components\Group::make([
                                    Components\TextEntry::make('member_status')
                                        ->badge()
                                        ->color(fn (User $user) => $user->member_status != 'Active' ? 'warning' : 'success'),
                                    Components\TextEntry::make('member_since')->label('Member Since')
                                ])->columns(2),
                            ])
                    ]),

            ]);
    }
}
