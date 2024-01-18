<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhoneModel extends Model
{
    use HasFactory, SoftDeletes;


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function series()
    {
        return $this->belongsTo(PhoneSeries::class, 'series_id');
    }

    public function phone_variant()
    {
        return $this->belongsToMany(Variant::class, 'phone_model_variants', 'phone_model_id', 'variant_id');
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'model_id');
    }
}