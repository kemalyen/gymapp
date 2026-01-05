<?php

namespace App\Filament\Resources\MemberResource\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Infolists\Components\TextEntry;
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
use Filament\Tables\Contracts\HasTable;

    class AttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected string $view = 'filament.resources.member-resource.pages.attendance-report';

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
                    ->schema([
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


    public function memberInfo(Schema $schema): Schema
    {
        return $schema
            ->record($this->user)
            ->schema([
               Section::make('Member')

                    ->schema([
                        Grid::make(2)
                            ->schema([

                                Group::make([
                                    TextEntry::make('name')->label('Name'),
                                    TextEntry::make('name')->label('Email'),
                                    TextEntry::make('phone')->label('Mobile Phone'),
                                ])->columns(3),

                                Group::make([
                                    TextEntry::make('member_status')
                                        ->badge()
                                        ->color(fn (User $user) => $user->member_status != 'Active' ? 'warning' : 'success'),
                                    TextEntry::make('member_since')->label('Member Since')
                                ])->columns(2),
                            ])
                    ]),

            ]);
    }
}
