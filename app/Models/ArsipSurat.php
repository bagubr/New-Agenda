<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipSurat extends Model
{
    protected $table = 'arsip_surat';
    protected $primaryKey = 'id';
    protected $fillable = [
        'no_agenda',
        'no_surat',
        'perihal',
        'asal_surat',
        'tgl_surat',
        'tgl_agenda',
        'keterangan',
        'file',
        'original_name',
        'file_size',
        'file_type',
    ];

    protected function surat_masuk()
    {
        return $this->belongsTo(Disposisi::class, 'disposisi', 'id');
    }
}
