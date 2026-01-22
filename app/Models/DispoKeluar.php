<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispoKeluar extends Model
{
    protected $table = 'dispo_keluar';
    protected $fillable = [
        'noagenda', 'periode', 'disposisi'
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
