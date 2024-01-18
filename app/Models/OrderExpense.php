<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderExpense extends Model
{
    use HasFactory,SoftDeletes;

    public $table = 'order_inventory_expense';

    public function order_expense()
    {
        return $this->belongsToMany(Inventory::class, 'order_inventory_expense');
    }
}
