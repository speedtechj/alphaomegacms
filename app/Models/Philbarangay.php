<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Philbarangay extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function philcity()
    {
        return $this->belongsTo(Philcity::class);
    }
}
