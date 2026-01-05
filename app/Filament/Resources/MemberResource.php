<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use App\Filament\Resources\MemberResource\Pages\ListMembers;
use App\Filament\Resources\MemberResource\Pages\CreateMember;
use App\Filament\Resources\MemberResource\Pages\EditMember;
use App\Filament\Resources\MemberResource\Pages\ViewMember;
use App\Filament\Resources\MemberResource\Pages\AttendanceReport;
use App\Filament\Resources\MemberResource\Pages\ListMembershipPlans;
use Filament\Schemas\Components\Group;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Forms\Components\LocalizedCountrySelect;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Spatie\Permission\Models\Role;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Actions as InfoAction;
use Filament;
use Filament\Facades\Filament as FacadesFilament;

class MemberResource extends Resource
{
    protected static ?int $navigationSort = 1;

    protected static ?string $label = 'members';

    protected static ?string $model = User::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contact')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(250),

                        TextInput::make('email')
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
                        TextInput::make('phone')
                            ->label('Mobile Phone')
                            ->required()
                            ->maxLength(250),

                        TextInput::make('address_line_1')

                            ->label('Address line')
                            ->required()
                            ->maxLength(250)->columnSpan(2),

                        TextInput::make('address_line_2')

                            ->label('Address line')
                            ->maxLength(250)->columnSpan(2),

                        Grid::make()
                            ->schema([

                                TextInput::make('post_code')
                                    ->label('Post Code')
                                    ->required()
                                    ->maxLength(25),

                                TextInput::make('city')
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
                SelectFilter::make('roles')
                    ->label('Member Type')
                    ->relationship(
                        'roles',
                        'name',
                        fn (Builder $query) => $query->whereIn('name', ['member', 'trial'])
                    ),
            ])
            ->recordActions([
                Action::make('attendence_report')
                    ->url(fn (User $user): string => static::getUrl('attendances', ['record' => $user->id]))
                    ->icon('heroicon-o-book-open'),

                Action::make('member_plans')
                    ->label('Membership Plan')
                    ->url(fn (User $user): string => static::getUrl('list-membership-plans', ['record' => $user->id]))
                    ->icon('heroicon-o-book-open'),

                EditAction::make(),
                ViewAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => ListMembers::route('/'),
            'create' => CreateMember::route('/create'),
            'edit' => EditMember::route('/{record}/edit'),
            'view' => ViewMember::route('/{record}'),
            'attendances' => AttendanceReport::route('/{record}/attendances'),
            'list-membership-plans' => ListMembershipPlans::route('/{record}/list-membership-plans'),
        ];
    }
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile')
                    ->headerActions([
                        Action::make('edit')
                            ->url(fn (User $user): string => route('filament.admin.resources.members.edit', $user->id)),
                        Action::make('view-memberships')
                            ->label('All membership plans')
                            ->url(fn (User $user): string => route('filament.admin.resources.members.list-membership-plans', $user->id))
                    ])
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                Group::make([
                                    TextEntry::make('name')->label('Name'),
                                    TextEntry::make('name')->label('Email'),
                                    TextEntry::make('member_since')->label('Member Since')
                                ])->columns(3),

                            ])
                    ]),


                Section::make('Membership')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('plan_name')->label('Membership Plan'),
                                TextEntry::make('member_status')
                                    ->badge()
                                    ->color(fn (User $user) => $user->member_status != 'Active' ? 'warning' : 'success'),
                                TextEntry::make('membership_started_at')->label('Started At'),
                                TextEntry::make('membership_ending_at')->label('Ending Date'),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Contact Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([

                                TextEntry::make('address')->label('Address')->html(true),
                                TextEntry::make('profile.phone')->label('Mobile Phone'),
                                TextEntry::make('membership_ending_at')->label('Ending Date'),
                            ]),
                    ])
                    ->collapsible(),

            ]);
    }
}
