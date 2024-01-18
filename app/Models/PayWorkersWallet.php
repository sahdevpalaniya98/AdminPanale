<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayWorkersWallet extends Model
{
    use HasFactory,SoftDeletes;
    public $table = 'pay_workers_wallet';
}
