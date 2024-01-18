<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PhoneModel;


class PhoneSeries extends Model
{
    use HasFactory,SoftDeletes;


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function phone_series()
    {
        return $this->hasMany(PhoneModel::class,'series_id');
    }
}
