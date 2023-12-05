<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Filament\Resources\MembershipResource;
use Filament\Actions;
use Filament\Resources\Pages\Page;
 
use App\Models\User;

use App\Models\Attendance;
use App\Models\Membership;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;

class ListMembershipPlans extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.resources.member-resource.pages.list-plans';


    public $user;
    public function mount($record)
    {
        $this->user = User::find($record);
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(Membership::query()->where('user_id', $this->user->id))
            ->columns([
                TextColumn::make('id')->label('Plan ID'),
                TextColumn::make('start_date')->label('Start Date')->dateTime('d M Y'),
                TextColumn::make('end_date')->label('End Date')->dateTime('d M Y'),
                TextColumn::make('plan_name')->label('Plan'),
                TextColumn::make('status_text')->label('Status')
                    ->badge()
                    ->color(fn (Membership $membership) => $membership->status ? 'success' : 'warning'),
                TextColumn::make('created_at')->label('Created Date')->dateTime('d M Y H:i'),
            ])
            ->filters([

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('start_date'),
                        DatePicker::make('end_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Action::make('edit')
                    ->url(fn (Membership $membership): string => route('filament.admin.resources.memberships.edit', ['record' => $membership->id]))
            ])
            ;
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
