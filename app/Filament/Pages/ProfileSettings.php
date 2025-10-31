<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileSettings extends Page
{
    use InteractsWithFormActions;
    
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $title = 'Profile Settings';
    
    protected static string $view = 'filament.pages.profile-settings';

    protected static string $routePath = 'profile-settings';
    
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'first_name' => Auth::user()->first_name,
            'last_name' => Auth::user()->last_name,
            'profile_picture' => Auth::user()->profile_picture,
        ]);
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->statePath('data'),
            ),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_picture')
                            ->label('Profile Picture')
                            ->image()
                            ->avatar()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('profile-pictures')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name')
                            ->label('Display Name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                
                Forms\Components\Section::make('Change Password')
                    ->description('Leave blank to keep your current password')
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Current Password')
                            ->password()
                            ->revealable()
                            ->visible(fn (Forms\Get $get) => !empty($get('new_password')) || !empty($get('new_password_confirmation')))
                            ->required(fn (Forms\Get $get) => !empty($get('new_password')) || !empty($get('new_password_confirmation')))
                            ->currentPassword(),
                        Forms\Components\TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->visible(fn (Forms\Get $get) => !empty($get('current_password')) || !empty($get('new_password_confirmation')))
                            ->required(fn (Forms\Get $get) => !empty($get('current_password')) || !empty($get('new_password_confirmation'))),
                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->same('new_password')
                            ->visible(fn (Forms\Get $get) => !empty($get('current_password')) || !empty($get('new_password')))
                            ->required(fn (Forms\Get $get) => !empty($get('current_password')) || !empty($get('new_password'))),
                    ])->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Save Changes')
            ->submit('save');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Auth::user();

        // Update basic info
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'first_name' => $data['first_name'] ?? null,
            'last_name' => $data['last_name'] ?? null,
            'profile_picture' => $data['profile_picture'] ?? null,
        ]);

        // Update password if provided
        if (!empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
            $user->save();
        }

        Notification::make()
            ->title('Profile updated successfully!')
            ->success()
            ->send();
    }

    public function getTitle(): string
    {
        return 'Profile Settings';
    }
}
