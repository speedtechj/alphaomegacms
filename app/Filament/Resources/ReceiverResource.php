<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Philcity;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Philbarangay;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReceiverResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReceiverResource\RelationManagers;

class ReceiverResource extends Resource
{
    protected static ?string $model = Receiver::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Customers';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Receiver Information')
                            ->schema([
                                Forms\Components\Select::make('sender_id')
                                    ->label('Sender')
                                    ->placeholder('Select Sender')
                                    ->searchable()
                                    ->preload()
                                    ->relationship('sender', 'full_name')
                                    ->required(),
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->unique(ignoreRecord: true)
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                    Forms\Components\TextInput::make('Home_number')
                                    ->mask('+63(999) 999-9999')
                                    ->maxLength(255),
                                    Forms\Components\TextInput::make('Mobile_number')
                                    ->unique(ignoreRecord: true)
                                    ->mask('+63(999) 999-9999')
                                    ->required()
                                    ->maxLength(255),
                            ])
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Address Information')
                            ->schema([
                                Forms\Components\TextInput::make('Address')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('philprovince_id')
                                    ->label('Province')
                                    ->relationship('philprovince', 'name')
                                    ->placeholder('Select Province')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $set('philcity_id', null);
                                        $set('philbarangay_id', null);
                                    }),
                                Forms\Components\Select::make('philcity_id')
                                    ->label('City')
                                    ->placeholder('Select City')
                                    ->searchable()
                                    ->live()
                                    ->preload()
                                    ->required()
                                    ->options(fn (Get $get): Collection => Philcity::query()
                                        ->where('philprovince_id', $get('philprovince_id'))
                                        ->pluck('name', 'id'))
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $set('philbarangay_id', null);
                                    }),
                                Forms\Components\Select::make('philbarangay_id')
                                    ->label('Barangay')
                                    ->placeholder('Select Barangay')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->options(fn (Get $get): Collection => Philbarangay::query()
                                        ->where('philcity_id', $get('philcity_id'))
                                        ->pluck('name', 'id')),
                                        Forms\Components\TextInput::make('zip_code')
                                        ->required()
                                        ->maxLength(255),
                            ])
                    ]),

                
                    Forms\Components\FileUpload::make('docs')
                    ->label('Document Attachements')
                    ->multiple()
                    ->downloadable()
                    ->previewable()
                    ->openable()
                    ->disk('public')
                    ->directory('receiverfile')
                    ->visibility('private')
                    ->reorderable()
                    ->columnSpanFull(),
                Forms\Components\MarkdownEditor::make('notes')
                    ->columnSpanFull(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Receiver Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Home_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Mobile_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('philprovince.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('philcity.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('philbarangay.name')
                    ->numeric()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable(),
                    Tables\Columns\ImageColumn::make('docs')
                    ->label('Document Attachements')
                    ->square(),
                Tables\Columns\TextColumn::make('notes'),
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
            'index' => Pages\ListReceivers::route('/'),
            'create' => Pages\CreateReceiver::route('/create'),
            'edit' => Pages\EditReceiver::route('/{record}/edit'),
        ];
    }
}
