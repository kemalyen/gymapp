<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
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
use Filament\Infolists\Infolist;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 5;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
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
                Tables\Filters\SelectFilter::make('roles')
                    ->relationship(
                        'roles',
                        'name',
                        fn (Builder $query) => $query->whereIn('name', ['trainer', 'staff', 'sales'])
                    ),
            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }


    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Components\Section::make('Profile')
                    ->schema([
                        Components\Group::make([
                            Components\TextEntry::make('name')->label('Name'),
                            Components\TextEntry::make('email')->label('Email'),
                            Components\TextEntry::make('profile.phone')->label('Mobile Phone'),
                            Components\TextEntry::make('user.role')->label('Role'),
                        ])->columns(3),

                    ]),
                Components\Section::make('Contact Information')
                    ->schema([
                        Components\Grid::make(1)
                            ->schema([
                                Components\TextEntry::make('address')->label('Address')->html(true),
                            ]),
                    ])

            ]);
    }
}
