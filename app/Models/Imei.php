<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Imei extends Model
{
    use SoftDeletes,LogsActivity;
    protected $table = 'mobile_imei_details';
}
