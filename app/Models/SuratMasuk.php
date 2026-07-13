<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    const UPDATED_AT = null;
    const CREATED_AT = 'time';

    protected $fillable = [
        'jns', 'asal', 'tanggal', 'no_surat', 'perihal', 'tgl_agenda', 'no_agenda', 'periode', 'jam', 'tmpt', 'acara', 'time', 'penerima', 'user', 'publish', 'note'
    ];

    protected $appends = [
        'jenis',
        'disposisi_all',
        'disposisi_keterangan',
    ];

    public function getJenisAttribute()
    {
        return $this->jns == "1" ? 'Undangan': ($this->jns == "2" ? "Non Undangan" : "Usulan Pembangunan");
    }

    public function getDisposisiAllAttribute()
    {
        $array = $this->dispomasuk()->pluck('disposisi')->toArray();
        return implode(', ', Disposisi::whereIn('id', $array)->groupBy('disposisi')->get()->pluck('disposisi')->toArray());
    }

    public function getDisposisiKeteranganAttribute()
    {
        $array = $this->dispomasuk()->get(['disposisi', 'tindak', 'ket'])->toArray();
        $array = array_map(function ($item) {
            return [
                'disposisi' => Disposisi::find($item['disposisi'])->disposisi,
                'tindak' => $item['tindak'],
                'keterangan' => $item['ket'],
            ];
        }, $array);
        return implode(PHP_EOL , array_map(function ($item) {
            return $item['disposisi'] . " (Tindak: " . (is_array(json_decode($item['tindak'])) ? implode(', ', json_decode($item['tindak'])) : $item['tindak']) . ") (Keterangan: " . $item['keterangan'] . ")";
        }, $array));
    }

    public function dispomasuk()
    {
        return $this->hasMany(DispoMasuk::class, 'no_agenda', 'no_agenda');
    }

    public function arsipSurat()
    {
        return $this->hasMany(\App\Models\ArsipSurat::class, 'no_agenda', 'no_agenda');
    }
    
}
