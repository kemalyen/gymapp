<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\EditAction;
use App\Filament\Resources\MembershipResource\Pages\ListMemberships;
use App\Filament\Resources\MembershipResource\Pages\CreateMembership;
use App\Filament\Resources\MembershipResource\Pages\EditMembership;
use App\Filament\Resources\MembershipResource\Pages;
use App\Filament\Resources\MembershipResource\RelationManagers;
use App\Models\Plan;
use App\Models\Membership;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns(1)->schema([
                        Select::make('user_id')
                            ->label('Member Name')
                            ->options(
                                fn () => User::query()->role(['member'])->pluck('name', 'id'),
                            )
                            ->searchable()
                            ->required(),
                    ])->hiddenOn('edit'),
                Section::make()
                    ->columns(2)->schema([
                        Select::make('plan_id')
                            ->label('Available Plans')
                            ->options(fn () => Plan::all()->pluck('name', 'id'),)
                            ->default(0)
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([1 => 'Active', 0 => 'Inactive'])
                            ->default(0)
                            ->required(),
                    ]),
                Section::make()
                    ->columns(2)->schema(
                        [
                            DatePicker::make('start_date'),
                            DatePicker::make('end_date'),

                        ]
                    ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Membership::query()->orderByDesc('created_at'))
            ->columns([
                TextColumn::make('id')->label('Membership ID')
                    ->sortable()
                    ->numeric(),
                TextColumn::make('member_name'),
                TextColumn::make('plan_name'),
                TextColumn::make('status_text')->label('Status')
                    ->badge()
                    ->color(fn (Membership $membership) => $membership->status ? 'success' : 'warning'),

                TextColumn::make('start_date')->label('Start Date')->dateTime('d M Y')->sortable(),
                TextColumn::make('end_date')->label('End Date')->dateTime('d M Y')->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Member Name')
                    ->searchable()
                    ->options(
                        fn () => User::query()->role(['member', 'trial'])->pluck('name', 'id'),
                    ),

                SelectFilter::make('status')
                    ->options([
                        '0' => 'Inactive', '1' => 'Active'
                    ]),

                SelectFilter::make('plan_id')
                    ->label('Plan')
                    ->options(
                        fn () => Plan::all()->pluck('name', 'id'),
                    ),
                Filter::make('created_at')
                    ->schema([
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
                    }),

            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMemberships::route('/'),
            'create' => CreateMembership::route('/create'),
            'edit' => EditMembership::route('/{record}/edit'),
        ];
    }
}
