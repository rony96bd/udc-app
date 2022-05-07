<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brid extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'brid',
        'status',
        'id_type',
        'rate',
        'message'
    ];
}
