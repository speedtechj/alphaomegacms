<?php

namespace App\Filament\Resources;

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
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static ?string $navigationGroup = 'Customers';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Transaction Information')
                ->schema([
                    Forms\Components\Select::make('sender_id')
                ->relationship('sender', 'full_name')
                ->label('Sender Name')
                ->live()
                ->afterStateUpdated(fn(Set $set) => $set('receiver_id', null)),
                Forms\Components\Select::make('receiver_id')
                ->label('Receiver Name')
                ->required()
                ->options(fn (Get $get): Collection => Receiver::query()
                ->where('sender_id', $get('sender_id'))
                ->pluck('full_name', 'id')),
                Forms\Components\Select::make('servicetype_id')
                ->relationship('servicetype', 'name')
                ->label('Service Type'),
                Forms\Components\Select::make('boxtype_id')
                ->relationship('boxtype', 'name')
                ->label('Box Type'),
                Forms\Components\TextInput::make('price')
                ->label('Price'),
                Forms\Components\TextInput::make('manual_invoice')
                ->mask('AO-9999999')
                ->label('Manual Invoice'),
                Forms\Components\DatePicker::make('booked_date')
                ->label('Pickup/Dropoff Date')
                ->native(false)
                ->closeOnDateSelection(),
                Forms\Components\FileUpload::make('docs')
                            ->label('Document Attachements')
                            ->multiple()
                            ->downloadable()
                            ->previewable()
                            ->openable()
                            ->disk('public')
                            ->directory('transfile')
                            ->visibility('private'),
                            Forms\Components\MarkdownEditor::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                
                ])->columns(2)
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('generated_invoice')
                    ->label('Generated Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.full_name')
                    ->label('Receiver Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicetype.name')
                    ->label('Service Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('boxtype.name')
                    ->label('Box Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booked_date')
                    ->label('Pickup/Dropoff Date')
                    ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('user.name')
                   ->label('created by')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'asc')
            ->filters([
                Filter::make('created_at')
                ->form([
                    Section::make('Pickup/Dropoff Date')
                    ->schema([
                    DatePicker::make('book_date')
                    ->native(false)
                    ->closeOnDateSelection(),
                    ])
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['book_date'],
                            fn (Builder $query, $date): Builder => $query->whereDate('booked_date', '=', $date),
                        );
                       
                })
            ]) ->filtersFormWidth('xl')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }    
}
