<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddressBook extends Model
{
    use HasFactory;
    protected $table = 'user_address_books';
    protected $fillable = [
        'user_id',
        'name',
        'address',
        'thana',
        'postal_code',
        'city',
        'country',
        'phone',
        'set_default',
    ];
}
