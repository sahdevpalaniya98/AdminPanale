<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PhoneSeries;

class Brand extends Model
{
    use HasFactory,SoftDeletes;


    public function phone_series()
    {
        return $this->hasMany(PhoneSeries::class,'brand_id');
    }
}
