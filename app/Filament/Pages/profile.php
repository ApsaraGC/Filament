<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class profile extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $model = User::class;

    public ?array $data = [];
    protected static string $view = 'filament.pages.profile';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $user = User::query()->with(['doctor', 'patient'])->find(auth()->user()->id);

        if ($user) {
            // Check if the user is a patient and has a birth_date
            if ($user->role === 'patient' && $user->patient && $user->patient->birth_date) {
                $birthDate = new \DateTime($user->patient->birth_date);
                $currentDate = new \DateTime();
                $age = $currentDate->diff($birthDate)->y; // Calculate age in years

                // Add the calculated age to the patient data array
                $userArray = $user->toArray();
                $userArray['patient']['age'] = $age; // Update age in the array
            } else {
                $userArray = $user->toArray();
            }

            // Fill the form with the user data (array)
            $this->form->fill($userArray);
        } else {
            Notification::make()
                ->title('User not found.')
                ->danger()
                ->send();
        }
    }

    public function form(Form $form): Form
    {
        $user = auth()->user();

        $schema = [
            TextInput::make('name')
                ->autofocus()
                ->required(),
            TextInput::make('email')
                ->required(),
        ];

        if ($user->role === 'doctor') {
            $schema = array_merge($schema, [
                Select::make('doctor.department_id')
                    ->label('Department')
                    ->relationship('doctor.department', 'name')
                    ->required(),
                TextInput::make('doctor.phone_number')
                    ->required()
                    ->maxLength(12)
                    ->default($user->doctor->number ?? ''),
                TextInput::make('doctor.position')
                    ->required()
                    ->default($user->doctor->position ?? ''),
                TextInput::make('doctor.shift')
                    ->required()
                    ->default($user->doctor->shift ?? ''),
                TextInput::make('doctor.experience')
                    ->required()
                    ->default($user->doctor->experience ?? ''),
                Select::make('doctor.gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'others' => 'Other',
                    ])
                    ->required()
                    ->default($user->doctor->gender ?? ''),

                FileUpload::make('doctor.image')
                    ->image()
                    ->default($user->doctor->image ?? '')
                    ->disk('public')
                    ->directory('image'),
            ]);
        } elseif ($user->role === 'patient') {
            $schema = array_merge($schema, [
                DatePicker::make('patient.birth_date')
                    ->required()
                    ->default($user->patient->birth_date ?? ''),
                // TextInput::make('patient.age')
                //     ->required()
                //     ->numeric()
                //     ->default($user->patient->age ?? ''),
                TextInput::make('patient.address')
                    ->required()
                    ->default($user->patient->address ?? ''),
                TextInput::make('patient.number')
                    ->required()
                    ->maxLength(12)
                    ->minLength(10)
                    ->default($user->patient->number ?? ''),
                TextInput::make('patient.description')
                    ->required()
                    ->default($user->patient->description ?? ''),
                Select::make('patient.gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])
                    ->required()
                    ->default($user->patient->gender ?? ''),
            ]);
        }

        return $form
            ->schema($schema)
            ->statePath('data')
            ->model($user);
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Update')
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update(): void
    {
        $user = User::query()->with(['doctor', 'patient'])->find(auth()->user()->id);

        if (!$user) {
            Notification::make()
                ->title('Update failed!')
                ->danger()
                ->send();
            return;
        }

        $formState = $this->form->getState();

        // Calculate age from birth_date
    // Check if 'patient' key exists in the form state and if 'birth_date' is set
    if (isset($formState['patient'])) {
        if (isset($formState['patient']['birth_date'])) {
            $birthDate = new \DateTime($formState['patient']['birth_date']);
            $currentDate = new \DateTime();
            $age = $currentDate->diff($birthDate)->y; // Calculate age in years
        } else {
            $age = $formState['patient']['age'] ?? null; // Fallback to the entered age if birth_date is not set
        }
    }
        $user->update([
            'name' => $formState['name'],
            'email' => $formState['email'],
        ]);

        if ($user->role === 'doctor') {
            $user->doctor()->updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'department_id' => $formState['doctor']['department_id'],
                    'position' => $formState['doctor']['position'],
                    'shift' => $formState['doctor']['shift'],
                    'experience' => $formState['doctor']['experience'],
                    'gender' => $formState['doctor']['gender'], // Ensure this line is included
                    'image' => $formState['doctor']['image'],
                    'phone_number' => $formState['doctor']['phone_number'],

                ]
            );
        } elseif ($user->role === 'patient') {
            $user->patient()->updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'birth_date' => $formState['patient']['birth_date'] ?? null,
                'age' => $age,
                'gender' => $formState['patient']['gender'] ?? null,
                'number' => $formState['patient']['number'] ?? null,
                'address' => $formState['patient']['address'] ?? null,
                'description' => $formState['patient']['description'] ?? null,

                ]
            );
        }

        Notification::make()
            ->title('Your Profile updated')
            ->success()
            ->send();
    }
}
