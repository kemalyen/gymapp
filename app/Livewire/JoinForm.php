<?php

namespace App\Livewire;

use App\Filament\Forms\Components\LocalizedCountrySelect;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextInputColumn;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class JoinForm extends Component  implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    protected $passwordRules = ['min:8'];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

                        TextInput::make('password')
                            ->label('password')
                            ->required()
                            ->password()
                            ->rules($this->passwordRules)
                            ->maxLength(50),

                        TextInput::make("password_confirmation")
                            ->label('password confirmation')
                            ->password()
                            ->same("password")
                            ->required(),
                    ]),

            ])->statePath('data');
    }


    public function create()
    {
        $input = $this->form->getState();
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules,
        ])->validate();

        User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password'])
        ]);
        session()->flash('message', 'Thank you very much joining us!');
        return redirect()->route('welcome');
    }

    public function render()
    {
        return view('livewire.join-form');
    }
}
