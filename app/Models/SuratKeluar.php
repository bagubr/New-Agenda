<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $fillable = [
        'jns', 'asal', 'tanggal', 'no_surat', 'perihal', 'tgl_agenda', 'no_agenda', 'periode', 'jam', 'tempat', 'acara'
    ];

    protected $appends = [
        'jenis',
    ];

    protected function getJenisAttribute()
    {
        return $this->jns == "1" ? 'Undangan':'Non Undangan';
    }

    protected function disposisi()
    {
        return $this->hasMany(DispoKeluar::class, 'noagenda', 'no_agenda');
    }
}
