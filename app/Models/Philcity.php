<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Philcity extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function philprovince()
    {
        return $this->belongsTo(Philprovince::class);
    }
}
