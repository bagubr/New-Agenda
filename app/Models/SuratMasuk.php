<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    const UPDATED_AT = 'time';
    const CREATED_AT = 'time';
    protected $primaryKey = 'no_agenda';

    protected $fillable = [
        'jns', 'asal', 'tanggal', 'no_surat', 'perihal', 'tgl_agenda', 'no_agenda', 'periode', 'jam', 'tmpt', 'acara', 'time', 'penerima', 'user', 'publish', 'note'
    ];

    protected $appends = [
        'jenis',
        'disposisi_all'
    ];

    public function getJenisAttribute()
    {
        return $this->jns == "1" ? 'Undangan':'Non Undangan';
    }

    public function getDisposisiAllAttribute()
    {
        $array = $this->dispomasuk()->pluck('disposisi')->toArray();
        return implode(', ', Disposisi::whereIn('id', $array)->groupBy('disposisi')->get()->pluck('disposisi')->toArray());
    }

    public function dispomasuk()
    {
        return $this->hasMany(DispoMasuk::class, 'noagenda', 'no_agenda');
    }
    
}
