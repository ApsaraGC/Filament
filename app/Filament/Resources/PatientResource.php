<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ColorEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->options(User::where('role', 'patient')->pluck('name', 'id'))
                    ->label('Patient Name')
                    ->required(),
                Forms\Components\TextInput::make('address')
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->label('Phone Number')
                    ->icon('heroicon-m-phone')
                    ->numeric(),
                Forms\Components\TextInput::make('age')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('birth_date')
                    ->required(),
                Forms\Components\Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable()
                    ->label('Patient Name'),
                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->label('Address'),
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
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
                SelectFilter::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other',
                    ])->indicator('Gender'),
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                TextEntry::make('user.name')->tooltip('Patient Name')
                    ->icon('heroicon-m-user')
                    ->iconColor('primary')
                    ->color('primary')
                    ->weight(FontWeight::Bold)
                    ->fontFamily(FontFamily::Mono)
                    ->label('Name'),

                TextEntry::make('user.email')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('primary')
                    ->label('Email'),
                TextEntry::make("id")
                    ->label('Patient ID')
                    ->color('success')
                    ->weight(FontWeight::Light)
                    ->fontFamily(FontFamily::Serif),
                TextEntry::make('address')
                    ->weight(FontWeight::Bold)
                    ->fontFamily(FontFamily::Sans)
                    ->color('success'),
                TextEntry::make('gender')
                    ->color('success')
                    ->icon('heroicon-m-question-mark-circle')
                    ->weight(FontWeight::Bold)

                    ->fontFamily(FontFamily::Sans),
                TextEntry::make('birth_date')
                    ->color('success')
                    ->icon('heroicon-m-calendar')
                    ->weight(FontWeight::Bold)

                    ->fontFamily(FontFamily::Sans),
                    TextEntry::make('created_at')
                    ->label('Created At')
                    ->icon('heroicon-m-calendar')
                    ->iconColor('primary'),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->icon('heroicon-m-calendar')
                    ->iconColor('primary'),

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
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'view' => Pages\ViewPatients::route('/{record}'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
