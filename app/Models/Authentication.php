<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authentication extends Model
{
    use HasFactory;
    protected $table = 'authentications';
    protected $fillable = [
        'user_name',
        'email',
        'password',
    ];
}
