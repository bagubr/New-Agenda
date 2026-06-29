<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $table = 'disposisi';
    public $timestamps = false;
    protected $fillable = [
        'disposisi', 'role', 'aktif', 'devisi'
    ];
}
