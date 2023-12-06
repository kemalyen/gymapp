<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\LocalizedCountrySelect;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Spatie\Permission\Models\Role;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;


use Filament\Infolists\Components\Actions\Action as InfoAction;
use Filament;
use Filament\Facades\Filament as FacadesFilament;

class MemberResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'members';

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Contact')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(250),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->required()
                            ->maxLength(250)
                            ->unique(User::class, 'email', ignoreRecord: true),


                        Select::make('roles')->relationship(
                            'roles',
                            'name',

                            modifyQueryUsing: fn (Builder $query) => $query->whereIn('name', ['member', 'trial']),
                        )->required(),



                    ]),
                Section::make('Address')
                    ->relationship('profile')
                    ->schema([
                        Forms\Components\TextInput::make('profile.phone')
                            ->label('Mobile Phone')
                            ->required()
                            ->maxLength(250),

                        Forms\Components\TextInput::make('address_line_1')

                            ->label('Address line')
                            ->required()
                            ->maxLength(250)->columnSpan(2),

                        Forms\Components\TextInput::make('address_line_2')

                            ->label('Address line')
                            ->maxLength(250)->columnSpan(2),

                        Grid::make()
                            ->schema([

                                Forms\Components\TextInput::make('post_code')
                                    ->label('Post Code')
                                    ->required()
                                    ->maxLength(25),

                                Forms\Components\TextInput::make('city')
                                    ->label('City')
                                    ->required()
                                    ->maxLength(50),

                                LocalizedCountrySelect::make('country')
                                    ->label('country')
                                    ->default('GB')
                                    ->required(),
                            ])->columns(3)
                    ]),

            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role(['member', 'trial'])->orderByDesc('id'))
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('roles.name'),
                TextColumn::make('plan_name')->label('Membership Plan'),
                TextColumn::make('membership_ending'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship(
                        'roles',
                        'name',
                        fn (Builder $query) => $query->whereIn('name', ['member', 'trial'])
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('attendence_report')
                    ->url(fn (User $user): string => static::getUrl('attendances', ['record' => $user->id]))
                    ->icon('heroicon-o-book-open'),

                Tables\Actions\Action::make('member_plans')
                    ->label('Membership Plan')
                    ->url(fn (User $user): string => static::getUrl('list-membership-plans', ['record' => $user->id]))
                    ->icon('heroicon-o-book-open'),

                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
            'view' => Pages\ViewMember::route('/{record}'),
            'attendances' => Pages\AttendanceReport::route('/{record}/attendances'),
            'list-membership-plans' => Pages\ListMembershipPlans::route('/{record}/list-membership-plans'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make('Profile')
                    ->schema([
                        Components\Grid::make(1)
                            ->schema([
                                Components\Group::make([
                                    Components\TextEntry::make('name')->label('Name'),
                                    Components\TextEntry::make('name')->label('Email'),
                                    Components\TextEntry::make('member_since')->label('Member Since')
                                ])->columns(3),

                            ])
                    ]),


                Filament\Infolists\Components\Section::make('Membership')
                    ->schema([
                        Components\Grid::make(4)
                            ->schema([
                                Components\TextEntry::make('plan_name')->label('Membership Plan'),
                                Components\TextEntry::make('member_status')
                                    ->badge()
                                    ->color(fn (User $user) => $user->member_status != 'Active' ? 'warning' : 'success'),
                                Components\TextEntry::make('membership_started_at')->label('Started At'),
                                Components\TextEntry::make('membership_ending_at')->label('Ending Date'),
                            ]),
                    ])
                    ->collapsible(),

                Components\Section::make('Contact Information')
                    ->schema([
                        Components\Grid::make(3)
                            ->schema([

                                Components\TextEntry::make('address')->label('Address')->html(true),
                                Components\TextEntry::make('profile.phone')->label('Mobile Phone'),
                                Components\TextEntry::make('membership_ending_at')->label('Ending Date'),
                            ]),
                    ])
                    ->collapsible(),

            ]);
    }
}
