<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotulenKeluar extends Model
{
    protected $table = 'notulen_keluar';
    const UPDATED_AT = 'tgin';
    const CREATED_AT = 'tgin';
    protected $primaryKey = 'id';
    protected $fillable = [
        'noagenda',
        'periode',
        'filename',
        'original_name',
        'user',
        'note',
    ];
    
}
