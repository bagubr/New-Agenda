<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangRapat extends Model
{
    const UPDATED_AT = 'tgin';
    const CREATED_AT = 'tgin';
    protected $table = 'ruangrapat';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ruangrapat'
    ];
}
