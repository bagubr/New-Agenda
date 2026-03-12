<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asal extends Model
{
    protected $table = 'asal';
    protected $fillable = [
        'name', 'kode'
    ];
}
