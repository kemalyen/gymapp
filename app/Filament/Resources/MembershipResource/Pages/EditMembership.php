<?php

namespace App\Filament\Resources\MembershipResource\Pages;

use App\Filament\Resources\MembershipResource;
use App\Models\Membership;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Actions as InfoAction;

class EditMembership extends EditRecord
{
    protected static string $resource = MembershipResource::class;

    protected static string $view = 'filament.resources.membership-resource.pages.edit-membership';

    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }

    public function memberInfo(Infolist $infolist): Infolist
    {
        $user = User::find($this->record->user_id);
        return $infolist
            ->record($user)
            ->schema([
                Components\Section::make('Member')
                    ->headerActions([
                        InfoAction\Action::make('view information')
                            ->url(fn (User $user): string => route('filament.admin.resources.members.view', $user->id)),
                        InfoAction\Action::make('view-memberships')
                            ->label('All membership plans')
                            ->url(fn (User $user): string => route('filament.admin.resources.members.list-membership-plans', $user->id))
                    ])
                    ->schema([
                        Components\Grid::make(1)
                            ->schema([
                                Components\Group::make([
                                    Components\TextEntry::make('name')->label('Name'),
                                    Components\TextEntry::make('name')->label('Email'),
                                    Components\TextEntry::make('phone')->label('Mobile Phone'),
                                    Components\TextEntry::make('member_since')->label('Member Since')
                                ])->columns(4),
                            ])
                    ]),

            ]);
    }
}
