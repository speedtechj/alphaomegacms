<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use App\Models\Citycan;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincecan;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SenderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SenderResource\RelationManagers;


class SenderResource extends Resource
{
    protected static ?string $model = Sender::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Customers';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Sender Information')
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->label('First Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->label('Last Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('Home_number')
                                    ->label('Home Number')
                                    ->mask('(999) 999-9999')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('Mobile_number')
                                ->unique(ignoreRecord: true)
                                    ->label('Mobile Number')
                                    ->mask('(999) 999-9999')
                                    // ->unique('Mobile_number')
                                    ->required()
                                    ->maxLength(255),
                            ])


                    ]),
                Group::make()
                    ->schema([
                        Section::make('Address Information')
                            ->schema([
                                Forms\Components\TextInput::make('Address')
                                    ->label('Address')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('provincecan_id')
                                    ->label('Province')
                                    ->relationship('provincecan', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $set('citycan_id', null);
                                    }),
                                Forms\Components\Select::make('citycan_id')
                                    ->label('City')
                                    ->searchable()
                                    ->required()
                                    ->options(fn (Get $get): Collection => Citycan::query()
                                        ->where('provincecan_id', $get('provincecan_id'))
                                        ->pluck('name', 'id')),
                                Forms\Components\TextInput::make('postal_code')
                                    ->label('Postal Code')
                                    ->required()
                                    ->maxLength(255),
                            ])

                    ]),

                Group::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->required(),
                        Forms\Components\FileUpload::make('docs')
                            ->label('Document Attachements')
                            ->multiple()
                            ->downloadable()
                            ->previewable()
                            ->openable()
                            ->disk('public')
                            ->directory('senderfile')
                            ->visibility('private'),
                        // Forms\Components\TextInput::make('password')
                        //     ->password()
                        //     ->required()
                        //     ->maxLength(255)
                        //     ->default('password'),
                        Forms\Components\MarkdownEditor::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ])->columnSpanFull()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('account_number')
                    ->label('Customer Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Home_number')
                    ->label('Home Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Mobile_number')
                    ->label('Mobile Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Address')
                    ->label('Address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provincecan.name')
                    ->label('Province')
                    ->searchable(),
                Tables\Columns\TextColumn::make('citycan.name')
                    ->label('City')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postal_code')
                    ->label('Postal Code')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('docs')
                ->label('Document Attachements')
                ->square()
                    ->stacked(),
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
            'index' => Pages\ListSenders::route('/'),
            'create' => Pages\CreateSender::route('/create'),
            'edit' => Pages\EditSender::route('/{record}/edit'),
        ];
    }
}
