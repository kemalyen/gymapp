<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\LocalizedCountrySelect;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
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
                Forms\Components\TextInput::make('name')
                    ->label('Full Name')
                    ->required()
                    ->maxLength(250),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->required()
                    ->maxLength(250)
                    ->unique(User::class, 'email', ignoreRecord: true),


                Forms\Components\TextInput::make('phone')
                    ->label('Mobile Phone')
                    ->required()
                    ->maxLength(250),

                Forms\Components\TextInput::make('address_line_1')
                    ->label('Address line')
                    ->required()
                    ->maxLength(250)->columnSpan(2),

                Forms\Components\TextInput::make('address_line_2')
                    ->label('Address line')
                    ->required()
                    ->maxLength(250)->columnSpan(2),

                Forms\Components\TextInput::make('city')
                    ->label('city')
                    ->required()
                    ->maxLength(250),

                LocalizedCountrySelect::make('country')
                    ->label('county')
                    ->default('UK')
                    ->required(),

                Forms\Components\TextInput::make('post_code')
                    ->label('Post Code')
                    ->required()
                    ->maxLength(250),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->role(['member', 'trial']))
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('phone_number'),
                TextColumn::make('roles.name'),
                TextColumn::make('contract_name')->label('Membership Plan'),
                TextColumn::make('membership_ending')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('roles')
                    //->options(Role::whereIn('name', ['member', 'trial'])->pluck('name', 'name')->toArray())
                    ->relationship(
                        'roles',
                        'name',
                        fn (Builder $query) => $query->whereIn('name', ['member', 'trial'])
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()->slideOver(),
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
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([]);
    }
}
