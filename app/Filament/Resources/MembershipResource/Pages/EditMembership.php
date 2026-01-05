<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\MembershipResource;
use App\Models\Membership;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Infolists\Components;
use Filament\Infolists\Components\Actions as InfoAction;

class EditMembership extends EditRecord
{
    protected static string $resource = MembershipResource::class;

    protected string $view = 'filament.resources.membership-resource.pages.edit-membership';

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    public function memberInfo(Schema $schema): Schema
    {
        $user = User::find($this->record->user_id);
        return $schema
            ->record($user)
            ->schema([
                Section::make('Member')
                    ->headerActions([
                        Action::make('view information')
                            ->url(fn (User $user): string => route('filament.admin.resources.members.view', $user->id)),
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
                                    TextEntry::make('phone')->label('Mobile Phone'),
                                    TextEntry::make('member_since')->label('Member Since')
                                ])->columns(4),
                            ])
                    ]),

            ]);
    }
}
