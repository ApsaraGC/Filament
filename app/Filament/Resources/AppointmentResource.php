<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\ColorEntry;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Support\Facades\Auth;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('patient_id')
                ->label('Patient')
                ->options(function () {
                    $patients = Patient::with('user')->get();
                    return $patients->pluck('user.name', 'id')->filter(function ($name) {
                        return !is_null($name);
                    })->toArray();
                })
                ->required(),
            Forms\Components\Select::make('department_id')
            ->label('Department')
            ->options(function () {
                return Department::all()->pluck('name', 'id')->toArray();
            })
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $set('doctor_id', null);
            }),

        Forms\Components\Select::make('doctor_id')
            ->label('Doctor')
            ->options(function (callable $get) {
                $departmentId = $get('department_id');
                if ($departmentId) {
                    return Doctor::where('department_id', $departmentId)
                        ->with('user')
                        ->get()
                        ->pluck('user.name', 'id')
                        ->filter(function ($name) {
                            return !is_null($name);
                        })->toArray();
                }
                return [];
            })
            ->required()
            ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('schedule_id', null);
                }),
            // Forms\Components\Select::make('schedule_id')
            //     ->label('Available Schedule')
            //     ->options(function (callable $get) {
            //         $doctorId = $get('doctor_id');
            //         if ($doctorId) {
            //             return \App\Models\Schedule::where('doctor_id', $doctorId)
            //                 ->get()
            //                 ->pluck('formatted_time', 'id')  // Display formatted time
            //                 ->toArray();
            //         }
            //         return [];
            //     })
                // ->required(),

                // ->disabled(),
            Forms\Components\TextInput::make('status')
                ->required(),
            Forms\Components\DateTimePicker::make('date_time')
                ->required(),
            Forms\Components\TextInput::make('appointment_date')
                ->required(),
                // Table to display schedules
            Forms\Components\Placeholder::make('schedules_table')
            ->label('Doctor’s Schedules')
            ->content(function (callable $get) {
                $doctorId = $get('doctor_id');
                if ($doctorId) {
                    $schedules = Schedule::where('doctor_id', $doctorId)->get();
                    return view('partials.schedules_table', ['schedules' => $schedules]);
                }
                return '';
            }),
            // Add other fields as necessary
        ]);;
    }
    public static function table(Table $table): Table
    {
        $user = Auth::user();
        $isDoctor = $user->hasRole('doctor');
        $isPatient = $user->hasRole('patient');
        //$doctorId = $isDoctor ? Doctor::where('user_id', $user->id)->value('id') : null;
        return $table
        ->modifyQueryUsing(function (Builder $query) use ($isDoctor, $isPatient, $user) {
            if ($isDoctor) {
                $doctorId = Doctor::where('user_id', $user->id)->value('id');
                $query->where('doctor_id', $doctorId);
            } elseif ($isPatient) {
                $patientId = Patient::where('user_id', $user->id)->value('id');
                $query->where('patient_id', $patientId);
            } elseif (auth()->user()->role === 'admin') {
                // Admins can see all appointments
            }
        })
            ->columns([
                Tables\Columns\TextColumn::make('patient.user.name')
                    ->label('Patient Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->label('Doctor Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Deparmtent Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_time')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('doctor_id')
                    ->label('Doctor')
                    ->options(function () {
                        return Doctor::query()
                            ->with('user')
                            ->get()
                            ->pluck('user.name', 'id')
                            ->filter(function ($name) {
                                return !is_null($name);
                            })
                            ->toArray();
                    }),
                SelectFilter::make('patient_id')
                    ->label('Patient')
                    ->options(function () {
                        return Doctor::query()
                            ->with('user')
                            ->get()
                            ->pluck('user.name', 'id')
                            ->filter(function ($name) {
                                return !is_null($name);
                            })
                            ->toArray();
                    }),

                Filter::make('date_time')
                    ->form([
                        DatePicker::make('date_time')
                            ->label('Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            !empty($data['date_time']),
                            fn(Builder $query) => $query->whereDate('date_time', '=', Carbon::parse($data['date'])->format('Y-m-d'))
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if (!empty($data['date_time'])) {
                            $selectedDate = Carbon::parse($data['date_time'])->toFormattedDateString();
                            $indicators[] = Indicator::make('Appointment on ' . $selectedDate)
                                ->removeField('date_time');
                        }

                        return $indicators;
                    })

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('reschedule')
                ->label('Reschedule')
                ->icon('heroicon-o-calendar')
                ->action(function (Appointment $record, array $data) {
                    if (!$record->canReschedule()) {
                        abort(403, 'You are not authorized to reschedule this appointment.');
                    }

                    $record->date_time = Carbon::parse($data['date_time']);
                    $record->save();

                    // Send notification to the patient
                    $record->patient->user->notify(new Notification());
                })
                ->form([
                    Forms\Components\DateTimePicker::make('date_time')
                        ->label('New Appointment Date & Time')
                        ->required(),
                ])
                ->visible(fn (Appointment $record) => $record->canReschedule()),
        ])


            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            // ->query(function (Builder $query) use ($doctorId) {
            //     if ($doctorId) {
            //         $query->where('doctor_id', $doctorId);
            //     }
            // })
            ;

    }
//
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Appointment Details')
                    ->schema([
                        TextEntry::make('patient.user.name')
                            ->label('Patient Name'),
                        TextEntry::make('doctor.user.name')
                            ->label('Doctor Name'),
                        TextEntry::make('department.name')
                            ->label('Department Name'),
                        TextEntry::make('status')
                            ->label('Status'),
                        TextEntry::make('date_time')
                            ->label('Appointment Date & Time')
                            ->dateTime(),
                    ]),
                Section::make('Metadata')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
