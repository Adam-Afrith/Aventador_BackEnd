<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileSub extends Model
{
    use HasFactory;
    protected $table = 'file_subs';
    protected $fillable = [
        'mainid',
        'originalfilename',
        'file_type',
        'file_size',
        'hasfilename',
    ];
}
