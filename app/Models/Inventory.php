<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventory';

    public function payment_modes()
    {
        return $this->belongsToMany(PaymentMode::class, 'inventory_payment_mode', 'inventory_id', 'payment_mode_id')->withPivot('amount');
    }
    public function phone_damages()
    {
        return $this->belongsToMany(PhoneDamage::class, 'inventory_phone_damages', 'inventory_id', 'phone_damage_id')->withPivot('expense_amount');
    }
    public function expenses()
    {
        return $this->belongsToMany(Expense::class, 'inventory_expense', 'inventory_id', 'expense_id')->withPivot('amount');
    }
    public function employee()
    {
        return $this->hasOne(User::class, 'id', 'employee_id');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    public function phone_grade()
    {
        return $this->hasOne(PhoneGrade::class, 'id', 'phone_grade_id');
    }
    public function phone_brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    public function phone_serice()
    {
        return $this->belongsTo(PhoneSeries::class, 'series_id', 'id');
    }
    public function pay_worker()
    {
        return $this->belongsTo(PayWorker::class, 'pay_worker_id', 'id');
    }
    public function phone_model()
    {
        return $this->belongsTo(PhoneModel::class, 'model_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventory_variant()
    {
        return $this->belongsToMany(Variant::class, 'inventory_variants', 'inventory_id', 'variant_id');
    }

    public function inventory_items()
    {
        return $this->belongsToMany(Order::class, 'order_inventory_items', 'inventory_id', 'order_id')->withPivot('amount');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
