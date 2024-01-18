<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InventoryPhoneDamages extends Pivot
{
    public $table = 'inventory_phone_damages';

    public function phone_damages()
    {
        return $this->belongsTo(PhoneDamage::class, 'phone_damage_id', 'id');
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }
}