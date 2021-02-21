<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSettings extends Model
{
    use HasFactory;
    protected $table = 'store_settings';

    protected $fillable = [
        'meta_name',
        'meta_value',
    ];
}
