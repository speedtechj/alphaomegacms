<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transreference extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected static function booted()
{
    static::creating(function ($refinv) {
        // Custom invoice number generation logic, e.g., adding a prefix or suffix
        $lastref = Transreference::orderBy('reference_invoice', 'desc')->first();
        $refinv->reference_invoice = $lastref ? $lastref->reference_invoice + 1 : 1;
        $refinv->reference_invoice =  str_pad($refinv->reference_invoice, 6, '0', STR_PAD_LEFT);
    });
}
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Receiver::class);
    }
}
