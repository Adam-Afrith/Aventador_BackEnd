<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    protected $table = 'owners';
    protected $fillable = [
        'owner_name',
        'company_id',
        'bike_id',
        'description',
        'price'
    ];
}
