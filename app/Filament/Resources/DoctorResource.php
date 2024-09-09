<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Resources\DoctorResource\RelationManagers;
use App\Models\Doctor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('user_id')
                //     ->required()
                //     ->numeric(),
                    Forms\Components\Select::make('user_id')
                    ->options(User::where('role', 'doctor')->pluck('name', 'id'))
                    ->label('Doctor Name')
                    ->required(),
                Forms\Components\TextInput::make('position')
                    ->required(),
                Forms\Components\TextInput::make('gender')
                    ->required(),
                Forms\Components\TextInput::make('shift')
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->required(),
                Forms\Components\TextInput::make('experience')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->numeric(),
                Forms\Components\TextInput::make('department_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        // ->query(function (Builder $query) {
        //     $doctorId = auth()->user()->doctor->id ?? null;
        //     if ($doctorId) {
        //         $query->where('id', $doctorId);
        //     }
        // })
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shift')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('experience')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department_id')
                    ->numeric()
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                ->icon('heroicon-o-pencil')
                ->color('primary')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
            ])

            ;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Section::make("Doctor")
                 ->columns(2)
                    ->schema([
                        TextEntry::make('user.name')
                        ->label('Name')
                        ->icon('heroicon-m-user')
                        ->iconColor('primary')
                        ->color('primary')
                        ->weight(FontWeight::Bold)
                        ->fontFamily(FontFamily::Mono)
                        ,
                        TextEntry::make('user.email')
                        ->label('Email')
                        ->icon('heroicon-m-envelope')
                        ->iconColor('primary')
                        ->color('primary')
                        ->weight(FontWeight::Bold)
                        ->fontFamily(FontFamily::Sans),

                        TextEntry::make("id")
                        ->label('Doctor ID')
                        ->color('primary')
                        ->icon('heroicon-o-identification')
                        ->weight(FontWeight::Bold)
                        ->fontFamily(FontFamily::Serif),
                        TextEntry::make('position')
                        ->label('Position')
                        ->color('primary')
                        ->weight(FontWeight::Medium)
                        ->fontFamily(FontFamily::Mono),
                        TextEntry::make('gender')
                        ->label('Gender')
                        ->icon('heroicon-o-user')
                        ->weight(FontWeight::Bold)
                        ->fontFamily(FontFamily::Sans)
                        ->color('success'),
                        TextEntry::make('shift')
                        ->label('Shift')

                        ->color('primary')
                         ->weight(FontWeight::Bold)
                        ->fontFamily(FontFamily::Serif),
                        ImageEntry::make('image')
                        ->label('Profile Image')
                        ->width(100)
                        ->height(50)
                        ,
                        TextEntry::make('phone_number')
                        ->icon('heroicon-o-phone')
                         ->label('Phone Number')
                        ->color('success')
                        ->weight(FontWeight::Bold)
                        ->fontFamily(FontFamily::Sans),
                    ])

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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'view' => Pages\ViewDoctor::route('/{record}'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
