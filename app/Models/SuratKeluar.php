<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    const UPDATED_AT = 'time';
    const CREATED_AT = 'time';
    protected $primaryKey = 'no_agenda';
    protected $fillable = [
        'jns', 'asal', 'tanggal', 'no_surat', 'perihal', 'tgl_agenda', 'no_agenda', 'periode', 'jam', 'tmpt', 'acara', 'penandatangan', 'user', 'note', 'publish'
    ];

    protected $appends = [
        'jenis',
    ];

    protected function getJenisAttribute()
    {
        return $this->jns == "1" ? 'Undangan':'Non Undangan';
    }

    public function dispokeluar()
    {
        return $this->hasMany(DispoKeluar::class, 'noagenda', 'no_agenda');
    }
}
