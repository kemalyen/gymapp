<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Models\Attendance;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;

class AttendanceReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.resources.member-resource.pages.attendance-report';

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
            ]);
    }
}
