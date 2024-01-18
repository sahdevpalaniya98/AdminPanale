<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatusHistory extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'order_status_history';

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
