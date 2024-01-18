<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends Model
{
    use HasFactory,SoftDeletes;

    public function order()
    {
        return $this->hasMany(Order::class,'buyer_id');
    }
}
