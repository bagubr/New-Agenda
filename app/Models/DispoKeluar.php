<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispoKeluar extends Model
{
    protected $table = 'dispo_keluar';
    const UPDATED_AT = 'time';
    const CREATED_AT = 'time';
    protected $fillable = [
        'noagenda', 'periode', 'disposisi', 'nomor', 'role', 'user', 'ket'
    ];

    protected $appends = [
        'disposisi_name',
    ];

    protected function getDisposisiNameAttribute()
    {
        return $this->disposisi()->first()->disposisi;
    }

    protected function disposisi()
    {
        return $this->belongsTo(Disposisi::class, 'disposisi', 'id');
    }
}
