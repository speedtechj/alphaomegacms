<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Sender;
use App\Models\Boxtype;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Servicetype;
use App\Models\Transaction;
use Filament\Support\RawJs;
use App\Models\Transreference;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransreferenceResource\Pages;
use App\Filament\Resources\TransreferenceResource\RelationManagers;

class TransreferenceResource extends Resource
{
    protected static ?string $model = Transreference::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static ?string $label = 'Transaction';
    protected static bool $shouldRegisterNavigation = false;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make('Sender Information')
                            ->schema([
                                Forms\Components\Select::make('senderid')
                                    ->label('Sender Name')
                                    ->options(Sender::all()->pluck('full_name', 'id'))
                                    ->live()
                                    ->required()
                                    ->searchable()
                                    ->dehydrated(false)
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                        $senderinfo = Sender::where('id', $state)->first();
                                        $set('Province', $senderinfo->provincecan->name);
                                        $set('Address', $senderinfo->Address);
                                        $set('City', $senderinfo->citycan->name);
                                        $set('Mobile_number', $senderinfo->Mobile_number);
                                        $set('Email', $senderinfo->email);
                                        $set('receiverid', null);
                                    }),
                                Forms\Components\TextInput::make('Address')->label('Address')
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('Province')->label('Province')
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('City')->label('City')
                                    ->dehydrated(false),
                                Forms\Components\Select::make('Mobile_number')->label('Mobile Number')
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('Email')->label('Email')
                                    ->dehydrated(false),
                            ])->columns(3),
                        Section::make('Receiver Information')
                            ->schema([
                                Forms\Components\Select::make('receiverid')
                                    ->label('Receiver Name')
                                    ->options(fn (Get $get): Collection => Receiver::query()
                                        ->where('sender_id', $get('senderid'))
                                        ->pluck('full_name', 'id'))
                                    ->required()
                                    ->live()
                                    ->dehydrated(false)
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                        $receiverinfo = Receiver::where('id', $state)->first();
                                        $set('province', $receiverinfo->philprovince->name);
                                        $set('address', $receiverinfo->Address);
                                        $set('city', $receiverinfo->philcity->name);
                                        $set('mobile_number', $receiverinfo->Mobile_number);
                                        $set('email', $receiverinfo->email);
                                        $set('barangay', $receiverinfo->philbarangay->name);
                                    }),
                                Forms\Components\TextInput::make('address')->label('Address')
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('province')->label('Province')
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('city')->label('City')
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('barangay')->label('Barangay')
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('mobile_number')->label('Mobile Number')
                                    ->dehydrated(false),
                            ])->columns(3),
                    ]),
                Section::make('Other Information')
                    ->schema([
                        Forms\Components\Select::make('servicetypeid')
                            ->label('Service Type')
                            ->options(Servicetype::all()->pluck('name', 'id'))
                            ->required()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('manualinvoice')
                            ->label('Manual Invoice')
                            ->required()
                            ->mask('AO-9999999')
                            ->dehydrated(false),
                        Forms\Components\DatePicker::make('bookeddate')
                            ->label('Pickup / Dropoff Date')
                            ->dehydrated(false)
                            ->required()
                            ->native(false)
                            ->default(now())
                            ->closeOnDateSelection(),
                    ])->columns(3),


                Repeater::make('transaction')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('boxtype_id')
                            ->options(Boxtype::all()->pluck('name', 'id'))
                            ->label('Box Type')
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->readOnly()
                            ->required()
                            ->numeric()
                            ->default(1),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->inputMode('decimal'),
                    ])->columns(3)->columnSpanFull()
                    ->cloneable()
                    ->addActionLabel('Add Item')
                    ->mutateRelationshipDataBeforeCreateUsing(function (Get $get, array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['sender_id'] = $get('senderid');
                        $data['receiver_id'] = $get('receiverid');
                        $data['servicetype_id'] = $get('senderid');
                        $data['manual_invoice'] = $get('manualinvoice');
                        $data['booked_date'] = $get('bookeddate');

                        return $data;
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->filters([
                
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
            'index' => Pages\ListTransreferences::route('/'),
            'create' => Pages\CreateTransreference::route('/create'),
            'edit' => Pages\EditTransreference::route('/{record}/edit'),
        ];
    }
}
