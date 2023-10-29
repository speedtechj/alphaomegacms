<?php

namespace App\Exports;

use App\Models\Manifest;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ManifestExport implements ShouldAutoSize, FromQuery, WithMapping, WithHeadings
{
    use Exportable;
    public $booking;
    
    public function __construct(Collection $booking)
    {
       
        $this->booking = $booking;
    }
    public function query()
    {
        return  Manifest::wherekey($this->booking->pluck('id')->toArray());
       
        
    }
    public function map($booking): array
    {
        // dd($booking->senderaddress->citycan->name);
        return [
            $booking->generated_invoice,
            $booking->manual_invoice,
            '1',
            $booking->boxtype->name,
            $booking->batch->batch_number.' '. '-' .' '.$booking->batch->batch_year,
            $booking->sender->full_name,
            $booking->receiver->full_name,
            $booking->receiver->Address,
            $booking->receiver->philbarangay->name,
            $booking->receiver->philcity->name,
            $booking->receiver->philprovince->name,
            $booking->receiver->Mobile_number,


                    
        ];
    }
    public function headings(): array
    {
        return [
            'Generated Invoice',
            'Manual Invoice',
            'Quantity',
            'Box Type',
            'Batch Number',
            'Sender Name',
            'Receiver Name',
            'Address',
            'Barangay',
            'City',
            'Province',
            'Mobile Number'     
        ];
    }
}
