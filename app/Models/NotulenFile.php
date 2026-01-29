<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotulenFile extends Model
{
    protected $table = 'notulen_files';

    protected $fillable = [
        'notulen_id',
        'file',
        'original_name',
        'jenis',
    ];
}
