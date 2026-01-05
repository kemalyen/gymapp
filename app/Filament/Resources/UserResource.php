<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Group;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Filament\Forms\Components\LocalizedCountrySelect;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Spatie\Permission\Models\Role;
use Filament\Infolists\Components;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 5;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                    modifyQueryUsing: fn (Builder $query) => $query->whereIn('name', ['trainer', 'staff', 'sales']),
                )->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role(['trainer', 'staff', 'sales']))
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('roles.name'),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship(
                        'roles',
                        'name',
                        fn (Builder $query) => $query->whereIn('name', ['trainer', 'staff', 'sales'])
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }


    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Profile')
                    ->schema([
                        Group::make([
                            TextEntry::make('name')->label('Name'),
                            TextEntry::make('email')->label('Email'),
                            TextEntry::make('profile.phone')->label('Mobile Phone'),
                            TextEntry::make('user.role')->label('Role'),
                        ])->columns(3),

                    ]),
                Section::make('Contact Information')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextEntry::make('address')->label('Address')->html(true),
                            ]),
                    ])

            ]);
    }
}
