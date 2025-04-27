<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Resources\DoctorResource\RelationManagers;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Doctor Information')
                    ->description('Fill in the details of the doctor')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name', function($query){
                        $query->where('role', 'doctor');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrated(fn ($state) => filled($state))
                            
                    ]),
                    Forms\Components\TextInput::make('hospital_name')
                        ->label('Hospital Name')
                        ->required(),
                    Forms\Components\Select::make('speciality_id')
                        ->relationship('speciality', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Textarea::make('bio')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('experience')
                        ->numeric(),
                    
                    ])->columns(2)
                        ->columnSpan(2),
                Section::make('Doctor Profile & status')
                    ->description('Fill in the details of the doctor')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured'),
                        Forms\Components\FileUpload::make('image')
                            ->label('Profile Image')
                            ->disk('public')
                            ->directory('doctors')
                            ->preserveFilenames()
                    ])
                        ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Profile Image')
                    ->circular()
                    ->size(50),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Doctor Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('speciality.name')
                    ->label('Speciality')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hospital_name')
                    ->label('Hospital Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('experience')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'view' => Pages\ViewDoctor::route('/{record}'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
