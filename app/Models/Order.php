<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'order';

    public function buyer()
    {
        return $this->hasOne(Buyer::class, 'id', 'buyer_id');
    }
    public function pay_worker()
    {
        return $this->hasOne(PayWorker::class, 'id', 'pay_worker_id');
    }

    // public function payment_mode()
    // {
    //     return $this->belongsTo(PaymentMode::class, 'payment_mode_id', 'id');
    // }

    public function inventory_items()
    {
        return $this->belongsToMany(Inventory::class, 'order_inventory_items', 'order_id', 'inventory_id')->withPivot('amount');
    }

    public function payment_mode()
    {
        return $this->belongsToMany(PaymentMode::class, 'order_payment_mode', 'order_id', 'payment_mode_id')->withPivot('amount');
    }

    public function phone_damages()
    {
        return $this->belongsToMany(Expense::class, 'order_damages', 'order_id', 'phone_damage_id')->withPivot('amount');
    }

    public function order_history() {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function expenses()
    {
        return $this->belongsToMany(Expense::class, 'order_damages', 'order_id', 'phone_damage_id')->withPivot('amount');
    }

}
