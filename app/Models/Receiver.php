<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receiver extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'docs' => 'array',
    ];
    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function philprovince()
    {
        return $this->belongsTo(Philprovince::class);
    }
    public function philcity()
    {
        return $this->belongsTo(Philcity::class);
    }
    public function philbarangay()
    {
        return $this->belongsTo(Philbarangay::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
