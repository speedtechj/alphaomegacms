<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Sender;
use App\Models\Boxtype;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Philcity;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Servicetype;
use App\Models\Transaction;
use Filament\Support\RawJs;
use App\Models\Philbarangay;
use App\Models\Transreference;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
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
                                    ->relationship('sender', 'full_name')
                                    ->options(Sender::all()->pluck('full_name', 'id'))
                                    ->live()
                                    ->required()
                                    ->searchable()
                                    ->dehydrated(false)
                                    ->afterStateUpdated(function (Set $set, Get $get, $state) {

                                        $set('receiverid', null);
                                        $set('province', null);
                                        $set('address', null);
                                        $set('city', null);
                                        $set('mobile_number', null);
                                        $set('email', null);
                                        $set('barangay', null);
                                    })
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('first_name')
                                            ->label('First_name')
                                            ->required(),
                                        Forms\Components\TextInput::make('last_name')
                                            ->label('Last_name')
                                            ->required(),
                                        Hidden::make('user_id')->default(auth()->id()),

                                    ])
                                    ->manageOptionActions(function (Action $action) {
                                        return $action

                                            ->modalWidth('lg');
                                    })


                            ])->columns(3),
                        Section::make('Receiver Information')
                            ->schema([
                                Forms\Components\Select::make('receiverid')
                                    ->label('Receiver Name')
                                    ->relationship('receiver', 'full_name')
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
                                    })
                                    ->createOptionForm([
                                        Hidden::make('user_id')->default(auth()->id()),
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
                                                            ->maxLength(255),
                                                        Forms\Components\TextInput::make('Home_number')
                                                            ->mask('+63(999) 999-9999')
                                                            ->maxLength(255),
                                                        Forms\Components\TextInput::make('Mobile_number')
                                                            ->unique(ignoreRecord: true)
                                                            ->mask('+63(999) 999-9999')
                                                            ->required()
                                                            ->maxLength(255),
                                                    ])->columns(3),
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
                                                            
                                                            ->maxLength(255),
                                                    ])->columns(3),
                                            ]),

                                    ]),
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
                            Forms\Components\Select::make('batchid')
                            ->label('Batch Number')
                            ->options(Batch::all()->where('is_active', 1)->pluck('batch_number', 'id'))
                            ->required()
                            ->dehydrated(false),
                    ])->columns(4),


                Repeater::make('transaction')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('boxtype_id')
                            ->relationship('boxtype', 'name')
                            ->label('Box Type')
                            ->required(),
                        Forms\Components\TextInput::make('quantity')
                            ->readOnly()
                            ->required()
                            ->numeric()
                            ->default(1),
                        
                    ])->columns(2)->columnSpanFull()
                    ->cloneable()
                    ->addActionLabel('Add Item')
                    ->mutateRelationshipDataBeforeCreateUsing(function (Get $get, array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['sender_id'] = $get('senderid');
                        $data['receiver_id'] = $get('receiverid');
                        $data['servicetype_id'] = $get('senderid');
                        $data['manual_invoice'] = $get('manualinvoice');
                        $data['booked_date'] = $get('bookeddate');
                        $data['batch_id'] = $get('batchid');

                        return $data;
                    })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->filters([])

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
