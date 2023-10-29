<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Manifest extends Model
{
    use HasFactory;
    protected $table = 'transactions';
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
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
    public function philprovince(): BelongsTo
    {
        return $this->belongsTo(Philprovince::class);
    }
    public function philcity(): BelongsTo
    {
        return $this->belongsTo(Philcity::class);
    }
    public function philbarangay(): BelongsTo
    {
        return $this->belongsTo(Philbarangay::class);
    }
}
