<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Manifest;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Exports\ManifestExport;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Resources\ManifestResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ManifestResource\RelationManagers;

class ManifestResource extends Resource
{
    protected static ?string $model = Manifest::class;
    protected static ?string $navigationGroup = 'Customers';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('generated_invoice')
                ->label('Invoice Number'),
                Tables\Columns\TextColumn::make('manual_invoice')
                 ->label('Manual Invoice'),
                Tables\Columns\TextColumn::make('batch_id')
                    ->label('Batch Number')
                    ->getStateUsing(fn (Manifest $manifest) =>
                    $manifest->batch->batch_number . '-' . $manifest->batch->batch_year)
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicetype.name')
                   ->label('Service Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('boxtype.name')
                   ->label('Box Type')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.full_name')
                    ->label('Receiver Name')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('receiver.Address')
                    ->label('Address')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('receiver.philbarangay.name')
                    ->label('Barangay')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('receiver.philcity.name')
                    ->label('City')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('receiver.philprovince.name')
                    ->label('Province')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('receiver.Mobile_number')
                    ->label('Mobile Number')
                    ->sortable(),
                    
                    
            ])->recordUrl(null) 
            ->filters([
               SelectFilter::make('batch_id')
               ->label('Batch Number')
                ->relationship('batch', 'batch_number')
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                
                    Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn (Collection $records) => (new ManifestExport($records))->download('manifest.xlsx')),
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
            'index' => Pages\ListManifests::route('/'),
            'create' => Pages\CreateManifest::route('/create'),
            'edit' => Pages\EditManifest::route('/{record}/edit'),
        ];
    }
}
