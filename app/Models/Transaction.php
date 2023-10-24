<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'docs' => 'array',
    ];
    protected static function booted()
{
    static::creating(function ($refinv) {
        // Custom invoice number generation logic, e.g., adding a prefix or suffix
        $lastref = Transaction::orderBy('generated_invoice', 'desc')->first();
        $refinv->generated_invoice = $lastref ? $lastref->generated_invoice + 1 : 1;
        $refinv->generated_invoice =  str_pad($refinv->generated_invoice, 6, '0', STR_PAD_LEFT);
    });
}
    
    public function transreference()
    {
        return $this->belongsTo(Transreference::class);
    }
    public function boxtype(): BelongsTo
    {
        return $this->belongsTo(Boxtype::class);
    }
    public function sender(): BelongsTo
    {
        return $this->belongsTo(Sender::class);
    }
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Receiver::class);
    }
    public function servicetype(): BelongsTo
    {
        return $this->belongsTo(Servicetype::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
