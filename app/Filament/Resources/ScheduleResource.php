<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\User;
//use DeepCopy\Filter\Filter;
use Filament\Forms;
use Filament\Tables\Filters\Filter;

use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('doctor_id')
                    ->label('Doctor')
                    ->options(function () {
                        // Get the currently logged-in user
                        $userId = auth()->id();

                        // Retrieve the doctor associated with the logged-in user
                        $doctor = Doctor::where('user_id', $userId)->first();

                        // Return only the logged-in doctor's ID and name as the option
                        return $doctor ? [$doctor->id => $doctor->user->name] : [];
                    })
                    ->required(),
                Forms\Components\DateTimePicker::make('available_from')
                    ->required(),
                Forms\Components\DateTimePicker::make('available_to')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $userId = auth()->user()->id;
        return $table
        ->modifyQueryUsing(function (Builder $query) use ($userId) {
            if (auth()->user()->role === 'admin') {
                return;
            }
            if (auth()->user()->role === 'doctor') {

                $query->whereHas('doctor', function (Builder $query) use ($userId) {
                    $query->where('user_id', $userId);
                });
            }
        })
            ->columns([
                    Tables\Columns\TextColumn::make('doctor.user.name') // Show the doctor's name instead of ID
                    ->label('Doctor') // Set a label for the column
                    ->sortable(), // Make this column sortable

                Tables\Columns\TextColumn::make('available_from')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_to')
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
                ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('success'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

        ;
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
