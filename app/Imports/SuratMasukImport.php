<?php

namespace App\Imports;

use App\Models\DispoMasuk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SuratMasukImport implements ToCollection, WithStartRow
{
    public function startRow(): int
    {
        return 2; // Skips the first row (the header)
    }
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) 
        {
            $no_agenda = explode('/', @SuratMasuk::where('periode', date('Y'))->orderBy('no_agenda', 'desc')->first()->no_agenda)[0] ?? 0;
             if ($no_agenda == "") {
                $no_agenda = 0;
            }
            $no_agenda = str_pad($no_agenda + 1, 5, '0', STR_PAD_LEFT) . '/' . date('m') . '/' . date('Y');
            SuratMasuk::create([
                'no_agenda' => $no_agenda,
                'jns' => $row[1],
                'perihal' => $row[2],
                'tanggal' => date('Y-m-d', strtotime($row[3])),
                'tgl_agenda' => date('Y-m-d', strtotime($row[4])),
                'acara' => $row[5],
                'no_surat' => $row[6],
                'user' => 'import',
                'penerima' => 'import',
                'periode' => date('Y'),
                'asal' => $row[7],
                'note' => $row[8],
            ]);
            DispoMasuk::create([
                'nomor' => $row[6],
                'no_agenda' => $no_agenda,
                'disposisi' => $row[9],
                'role' => 'superadmin',
                'user' => 'import',
                'tindak' => $row[10],
                'ket' => $row[11],
            ]);
            DispoMasuk::create([
                'nomor' => $row[6],
                'no_agenda' => $no_agenda,
                'disposisi' => $row[12],
                'role' => 'superadmin',
                'user' => 'import',
                'tindak' => $row[13],
                'ket' => $row[14],
            ]);
            DispoMasuk::create([
                'nomor' => $row[6],
                'no_agenda' => $no_agenda,
                'disposisi' => $row[15],
                'role' => 'superadmin',
                'user' => 'import',
                'tindak' => $row[16],
                'ket' => $row[17],
            ]);
        }
    }
}
