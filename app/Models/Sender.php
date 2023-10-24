<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sender extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'docs' => 'array',
    ];
    protected static function booted()
{
    static::creating(function ($acctno) {
        // Custom invoice number generation logic, e.g., adding a prefix or suffix
        $lastacount = Sender::orderBy('account_number', 'desc')->first();
        $acctno->account_number =  $lastacount ? $lastacount->account_number + 1 : 1;
        $acctno->account_number =  str_pad($acctno->account_number, 6, '0', STR_PAD_LEFT);
    });
}
    public function provincecan(): belongsTo
    {
        return $this->belongsTo(Provincecan::class);
    }
    public function citycan(): belongsTo
    {
        return $this->belongsTo(Citycan::class);
    }
    public function transreference()
    {
        return $this->belongs(Transreference::class);
    }
    public function transaction()
    {
        return $this->belongs(Transaction::class);
    }
    public function receiver(): belongsTo
    {
        return $this->belongsTo(Receiver::class);
    }
}
