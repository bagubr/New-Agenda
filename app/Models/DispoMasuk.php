<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispoMasuk extends Model
{
    protected $table = 'dispo_masuk';
    const UPDATED_AT = 'time';
    const CREATED_AT = 'time';
    protected $fillable = [
        'noagenda', 'periode', 'disposisi', 'nomor', 'role', 'user', 'ket', 'tindak'
    ];

    protected $appends = [
        'disposisi_name',
    ];

    protected function getDisposisiNameAttribute()
    {
        return $this->dispo()->first()->disposisi;
    }

    protected function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'noagenda', 'no_agenda');
    }

    public function dispo()
    {
        return $this->belongsTo(Disposisi::class, 'disposisi', 'id');
    }
}
